<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Dao;

/**
 * Base interface for generic DAO.
 */
interface Generic
{
    /**
     * Create new entity.
     *
     * @param \TeqFw\Lib\Db\Api\Dao\Entity $dao
     * @param DataEntity $data
     * @return mixed
     */
    public function create(\TeqFw\Lib\Db\Api\Dao\Entity $dao, $data);

    /**
     * Get one entity by primary key or unique key.
     *
     * If:
     *      * $key is a simple type - primary key must contain one attribute only (first item will be used as a name);
     *      * $key is an array - filter by the key items will be applied.
     *
     * @param \TeqFw\Lib\Db\Api\Dao\Entity $dao
     * @param mixed $key
     * @return mixed
     */
    public function getOne(
        \TeqFw\Lib\Db\Api\Dao\Entity $dao,
        $key);

    public function getSet(
        \TeqFw\Lib\Db\Api\Dao\Entity $dao,
        $where = null,
        $bind = null,
        $order = null,
        $limit = null,
        $offset = null);
}