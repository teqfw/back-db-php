<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Connection;

/**
 * Framework wrapper for main database connection (Doctrine based). 80% of all applications require one database
 * connection only. This connection.
 */
class Main
    extends \Doctrine\DBAL\Connection
{
}