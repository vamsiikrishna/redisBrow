<?php

namespace RedisBrow\Helper;

use Predis;

/**
 * Class RedisHelper
 * @package RedisBrow\Helper
 */
class RedisHelper
{

    /**
     * @var Predis\Client
     */
    private $redis;

    /**
     * @param $host
     * @param $port
     */
    public function __construct($host, $port)
    {
        $this->redis = new Predis\Client("tcp://$host:$port");
    }

    /**
     * @return array
     */
    public function info()
    {
        return $this->redis->info();
    }

    /**
     * @return int
     */
    public function dbsize()
    {
        return $this->redis->dbsize();
    }
}