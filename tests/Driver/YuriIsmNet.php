<?php

namespace YameteTests\Driver;


class YuriIsmNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.yuri-ism.net/slide/series/magia_record/';
        $driver = new \Yamete\Driver\YuriIsmNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(50, count($driver->getDownloadables()));
    }
}
