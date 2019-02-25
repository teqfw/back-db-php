<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Dao\Entity;


/**
 * DAO to handle annotated entities (CRUD operations).
 * Entity should be annotated with Doctrine annotations.
 */
class Anno
{
    private const ANNO_COLUMN = 'column';
    private const ANNO_COLUMN_PROP_TYPE = 'type';
    private const ANNO_ID = 'id';
    private const ANNO_TABLE = 'table';
    private const ANNO_TABLE_PROP_NAME = 'name';
    /**
     * Alias for main table.
     *
     * @var string
     */
    private const AS = 'entity';
    private const SELECT_ALL = '*';

    /** @var \TeqFw\Lib\Db\Api\Connection\Main */
    private $conn;
    /** @var \TeqFw\Lib\Dem\Api\Helper\Util\Path */
    private $hlpPath;
    /**
     * @var array [class][property]=>[annotations]
     */
    private $mapClassProps = [];
    /**
     * @var array [class]=>[attr1, attr2, ...]
     */
    private $mapClassToPrimaryKey = [];
    /**
     * @var array [class]=>[table_name]
     */
    private $mapClassToTable = [];

    public function __construct(
        \TeqFw\Lib\Db\Api\Connection\Main $conn,
        \TeqFw\Lib\Dem\Api\Helper\Util\Path $hlpPath
    ) {
        $this->conn = $conn;
        $this->hlpPath = $hlpPath;
    }

    public function create($data)
    {
        $result = null;
        $class = get_class($data);
        $table = $this->getTableName($class);
        $fields = (array)$data;
        $norm = $this->normalizeFields($class, $fields);
        $withoutNullPK = $this->unsetPrimaryKeyNulls($class, $norm);
        $this->conn->insert($table, $withoutNullPK);
        $result = $this->conn->lastInsertId();
        return $result;
    }

    public function deleteOne($pk)
    {
        // TODO: Implement deleteOne() method.
    }

    public function deleteSet($where)
    {
        // TODO: Implement deleteSet() method.
    }

    public function getEntityPath()
    {
        $name = $this->getEntityName();
        $ns = $this->getEntityNamespace();
        $result = $this->hlpPath->normalizeRoot($name, $ns);
        return $result;
    }

    public function getOne($class, $key)
    {
        $result = null;
        /* compose filter parameters */
        $bind = [];
        if (is_array($key)) {
            $bind = $key;
        } else {
            $pkey = $this->getPrimaryKey($class);
            $first = reset($pkey);
            $bind[$first] = $key;
        }
        /* compose query */
        $table = $this->getTableName($class);
        $qb = $this->conn->createQueryBuilder();
        $qb->select(self::SELECT_ALL);
        $qb->from($table, self::AS);
        foreach ($bind as $attr => $value) {
            $qb->andWhere("$attr=:$attr");
        }
        $qb->setParameters($bind);
        $sql = $qb->getSQL();
        /* execute query */
        $stmt = $qb->execute();
        $all = $stmt->fetchAll(
            \Doctrine\DBAL\FetchMode::CUSTOM_OBJECT,
            $class
        );
        if (count($all) == 1) {
            $result = reset($all);
        }
        return $result;
    }

    /**
     * Get array of the primary key attributes.
     *
     * @param string $class
     * @return string[]
     * @throws \Exception
     */
    private function getPrimaryKey($class)
    {
        if (!isset($this->mapClassToTable[$class])) {
            $this->parseAnnotations($class);
        }
        return $this->mapClassToPrimaryKey[$class];
    }

    public function getSet($class, $bind = null, $where = null, $order = null, $limit = null, $offset = null)
    {
        /* get set by KEY ($bind is the key, cause $where is null) */
        if (is_null($where)) {
            $where = [];
            if (is_array($bind)) {
                foreach ($bind as $attr => $value) {
                    $where[] = "$attr=:$attr";
                }
            }
        }
        /* compose query */
        $table = $this->getTableName($class);
        $qb = $this->conn->createQueryBuilder();
        $qb->select(self::SELECT_ALL);
        $qb->from($table, self::AS);
        foreach ($where as $one) {
            $qb->andWhere($one);
        }
        if (is_array($bind))
            $qb->setParameters($bind);
        if (is_array($order)) {
            foreach ($order as $field => $direction) {
                $qb->orderBy($field, $direction);
            }
        }
        if ($limit)
            $qb->setMaxResults($limit);
        $sql = $qb->getSQL();

        /* execute query */
        $stmt = $qb->execute();
        $result = $stmt->fetchAll(
            \Doctrine\DBAL\FetchMode::CUSTOM_OBJECT,
            $class
        );
        return $result;
    }

    private function getTableName($class)
    {
        if (!isset($this->mapClassToTable[$class])) {
            $this->parseAnnotations($class);
        }
        return $this->mapClassToTable[$class];
    }

    private function normalizeFields($class, $fields)
    {
        $result = [];
        foreach ($fields as $field => $value) {
            $normalized = $value;
            if (isset($this->mapClassProps[$class][$field][self::ANNO_COLUMN])) {
                $anno = $this->mapClassProps[$class][$field][self::ANNO_COLUMN];
                if (isset($anno[self::ANNO_COLUMN_PROP_TYPE])) {
                    $type = $anno[self::ANNO_COLUMN_PROP_TYPE];
                    if (
                        (
                            ($type == \Doctrine\DBAL\Types\Type::DATETIME) ||
                            ($type == \Doctrine\DBAL\Types\Type::DATE)
                        ) &&
                        $value instanceof \DateTime
                    ) {
                        $normalized = $value->format('Y-m-d H:i:s');
                    } elseif (
                    ($type == \Doctrine\DBAL\Types\Type::BOOLEAN)
                    ) {
                        $normalized = (int)$value;
                    }
                }
            }
            $result[$field] = $normalized;
        }

        return $result;
    }

    private function parseAnnotations($class)
    {
        $refClass = new \ReflectionClass($class);
        $annoClass = new \zpt\anno\Annotations($refClass);
        $all = $annoClass->asArray();
        if (isset($all[self::ANNO_TABLE][self::ANNO_TABLE_PROP_NAME])) {
            $table = $all[self::ANNO_TABLE][self::ANNO_TABLE_PROP_NAME];
            $this->mapClassToTable[$class] = $table;
            $pkey = [];
            foreach ($refClass->getProperties() as $refProp) {
                $propName = $refProp->name;
                $annoProp = new \zpt\anno\Annotations($refProp);
                $propAnnos = $annoProp->asArray();
                $this->mapClassProps[$class][$propName] = $propAnnos;
                if (isset($propAnnos[self::ANNO_ID])) {
                    $pkey[] = $propName;
                }
            }
            $this->mapClassToPrimaryKey[$class] = $pkey;
        } else {
            throw new \Exception("Class '$class' has no '@Table(name=...)' annotation.");
        }
    }

    private function unsetPrimaryKeyNulls($class, $fields)
    {
        $result = [];
        $pk = $this->mapClassToPrimaryKey[$class];
        foreach ($fields as $name => $value) {
            /* skip nulls in primary key fields */
            if (in_array($name, $pk) && is_null($value))
                continue;
            $result[$name] = $value;
        }
        return $result;
    }

    public function updateOne($data)
    {
        // TODO: Implement updateOne() method.
    }

    public function updateSet($data, $where)
    {
        // TODO: Implement updateSet() method.
    }
}