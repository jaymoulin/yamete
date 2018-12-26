<?php

namespace YameteTests\Driver;


class HentaiRead extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentairead.com/tth_15_5/';
        $driver = new \Yamete\Driver\HentaiRead();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
