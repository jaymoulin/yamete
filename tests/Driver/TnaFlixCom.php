<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class TnaFlixCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.tnaflix.com/gallery/jiggly-girls-01-197559641/image-1466052645';
        $driver = new \Yamete\Driver\TnaFlixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(250, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadLocale()
    {
        $url = 'https://www.tnaflix.com/en/gallery/desire-dojo-193357122';
        $driver = new \Yamete\Driver\TnaFlixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
