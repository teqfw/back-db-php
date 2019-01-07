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

    public function __construct(
        \TeqFw\Lib\Db\Api\Dao\Generic $generic
    ) {
        $this->generic = $generic;
    }

    public function create($data)
    {
        $entityName = static::ENTITY_NAME;
        $result = $this->generic->create($entityName, $data);
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