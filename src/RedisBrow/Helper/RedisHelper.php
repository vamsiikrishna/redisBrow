<?php

namespace RedisBrow\Helper;

use Predis;

class RedisHelper {

    private $redis;

    public function __construct($host,$port)
    {
        $this->redis = new Predis\Client("tcp://$host:$port");
    }
    public function info() {
        return $this->redis->info();
    }
    public function dbsize() {
        return $this->redis->dbsize();
    }
}