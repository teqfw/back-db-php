<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Api\Connection;

/**
 * Create database connections and place it into IoC-container.
 */
class Builder
{
    /** @var  \TeqFw\Lib\Di\Api\Container */
    private $container;

    public function __construct(
        \TeqFw\Lib\Di\Api\Container $container
    ) {
        assert($container instanceof \TeqFw\Lib\Di\Api\Container);
        $this->container = $container;
    }

    /**
     * Build database connection and place it into IoC-container.
     * Replace existing connection if exist.
     *
     * @param \TeqFw\Lib\Db\Api\Data\Cfg\Db $cfg
     */
    public function build(\TeqFw\Lib\Db\Api\Data\Cfg\Db $cfg)
    {
        $this->createConnectionSchema($cfg);
        $this->createConnectionQuery($cfg);
    }

    private function createConnectionQuery(\TeqFw\Lib\Db\Api\Data\Cfg\Db $cfg)
    {
        $configArray = [
            'charset' => $cfg->charset,
            'driver' => $cfg->driver,
            'hostname' => $cfg->host,
            'database' => $cfg->dbname,
            'username' => $cfg->user,
            'password' => $cfg->password

        ];
        $conn = new \TeqFw\Lib\Db\Api\Connection\Query($configArray);
        $this->container->add(\TeqFw\Lib\Db\Api\Connection\Query::class, $conn, true);
    }

    private function createConnectionSchema(\TeqFw\Lib\Db\Api\Data\Cfg\Db $cfg)
    {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = (array)$cfg;
        $connectionParams['wrapperClass'] = \TeqFw\Lib\Db\Api\Connection\Main::class;
        /** @var  $conn */
        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $this->container->add(\TeqFw\Lib\Db\Api\Connection\Main::class, $conn, true);
        $this->container->add(\Doctrine\DBAL\Connection::class, $conn, true);
        $this->container->add(\Doctrine\DBAL\Driver\Connection::class, $conn, true);
    }
}