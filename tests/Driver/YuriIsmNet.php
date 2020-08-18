<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class YuriIsmNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.yuri-ism.net/slide/series/magia_record/';
        $driver = new \Yamete\Driver\YuriIsmNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(81, count($driver->getDownloadables()));
    }
}
