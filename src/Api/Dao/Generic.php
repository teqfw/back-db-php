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
     * @param \TeqFw\Lib\Db\Api\Dao\Entity $repo
     * @param DataEntity $data
     * @return mixed
     */
    public function create(\TeqFw\Lib\Db\Api\Dao\Entity $repo, $data);
}