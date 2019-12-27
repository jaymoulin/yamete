<?php

namespace YameteTests\Driver;


class AuppCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://zh.a-upp.com/s/285564/';
        $driver = new \Yamete\Driver\AuppCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(192, count($driver->getDownloadables()));
    }
}
