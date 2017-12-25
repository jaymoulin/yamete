<?php

namespace YameteTests\Driver;


class Comicspornoxxx extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://comicspornoxxx.com/kaa-san-koibito-seikatsu-2/';
        $driver = new \Yamete\Driver\Comicspornoxxx();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
