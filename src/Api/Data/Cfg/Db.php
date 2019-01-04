<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Data\Cfg;

/**
 * Database connection configuration.
 *
 * see
 *  - http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html
 *  - https://framework.zend.com/manual/2.4/en/modules/zend.db.adapter.html
 */
class Db
    extends \TeqFw\Lib\Data
{
    public $charset;
    public $dbname;
    public $driver;
    public $host;
    public $password;
    public $port;
    public $user;
}