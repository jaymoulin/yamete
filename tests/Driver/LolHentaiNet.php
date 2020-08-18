<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class LolHentaiNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.lolhentai.net/index?/collections/view/862-ad674ec481efc2d4&start=0';
        $driver = new \Yamete\Driver\LolHentaiNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(69, count($driver->getDownloadables()));
    }
}
