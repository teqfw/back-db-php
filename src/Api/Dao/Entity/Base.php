<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Dao\Entity;


class Base
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
        $entityName = static::ENTITY_NAME;
        $entityNs = static::ENTITY_NS;
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

    public function getEntityPath()
    {
        $name = static::ENTITY_NAME;
        $ns = static::ENTITY_NS;
        $result = $this->hlpPath->normalizeRoot($name, $ns);
        return $result;
    }

    public function getOne($pk)
    {
        // TODO: Implement getOne() method.
    }

    public function getSet($where = null, $bind = null, $order = null, $limit = null, $offset = null)
    {
        // TODO: Implement getSet() method.
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