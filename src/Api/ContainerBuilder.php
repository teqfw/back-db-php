<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api;

/**
 * Place this module's objects into container.
 */
class ContainerBuilder
{
    private static function createConnectionSchema(
        \TeqFw\Lib\Di\Api\Container $container
    ) {
        $cfg = $container->get(\TeqFw\Lib\Db\Api\Data\Cfg\Db::class);
        $connectionParams = (array)$cfg;
        $connectionParams['wrapperClass'] = \TeqFw\Lib\Db\Api\Connection\Main::class;

        $config = new \Doctrine\DBAL\Configuration();
        /** @var  $conn */
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $container->add(\TeqFw\Lib\Db\Api\Connection\Main::class, $conn, true);
        $container->add(\Doctrine\DBAL\Connection::class, $conn, true);
        $container->add(\Doctrine\DBAL\Driver\Connection::class, $conn, true);
    }

    public static function populate(\TeqFw\Lib\Di\Api\Container $container)
    {
        self::createConnectionSchema($container);
    }
}