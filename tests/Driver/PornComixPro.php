<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class PornComixPro extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.porncomix.pro/gallery/os-simpson-chap6';
        $driver = new \Yamete\Driver\PornComixPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
