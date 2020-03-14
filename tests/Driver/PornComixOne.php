<?php

namespace YameteTests\Driver;


class PornComixOne extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.porncomix.one/gallery/johnpersons-candy#';
        $driver = new \Yamete\Driver\PornComixOne();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
