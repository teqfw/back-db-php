<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Dao\Entity;


use TeqFw\Lib\Db\Api\Dao\DataEntity;

abstract class Base
    implements \TeqFw\Lib\Db\Api\Dao\Entity
{
    /** @var \TeqFw\Lib\Db\Api\Dao\Generic */
    private $generic;
    /** @var \TeqFw\Lib\Dem\Api\Helper\Util\Path */
    private $hlpPath;

    public function __construct(
        \TeqFw\Lib\Db\Api\Dao\Generic $generic,
        \TeqFw\Lib\Dem\Api\Helper\Util\Path $hlpPath
    ) {
        $this->generic = $generic;
        $this->hlpPath = $hlpPath;
    }

    public function create($data)
    {
        $result = $this->generic->create($this, $data);
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

    protected abstract function getEntityName();

    public abstract function getEntityClass();

    protected abstract function getEntityNamespace();

    public function getEntityPath()
    {
        $name = $this->getEntityName();
        $ns = $this->getEntityNamespace();
        $result = $this->hlpPath->normalizeRoot($name, $ns);
        return $result;
    }

    public function getOne($key)
    {
        $result = $this->generic->getOne($this, $key);
        return $result;
    }


    public function getSet($where = null, $bind = null, $order = null, $limit = null, $offset = null)
    {
        $result = $this->generic->getSet($this, $where, $bind, $order, $limit, $offset);
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