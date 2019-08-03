<?php

namespace YameteTests\Driver;


class PornComixRe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://porncomix.re/online/romulo-mancin-pie-conundrum/';
        $driver = new \Yamete\Driver\PornComixRe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
