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
     * @param string $entityName
     * @param DataEntity $data
     * @return mixed
     */
    public function create($entityName, $data);
}