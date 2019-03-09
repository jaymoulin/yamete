<?php

namespace YameteTests\Driver;


class PornComixRe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://porncomix.re/online/spider-man-aunt-cumming/';
        $driver = new \Yamete\Driver\PornComixRe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
    }
}
