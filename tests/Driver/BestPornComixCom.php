<?php

namespace YameteTests\Driver;


class BestPornComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://bestporncomix.com/gallery/swat-kats-busted/';
        $driver = new \Yamete\Driver\BestPornComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
