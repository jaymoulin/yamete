<?php

namespace YameteTests\Driver;


class LolHentaiPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.lolhentai.pro/galleries/bunny-riven-o-taberu-chogath-san';
        $driver = new \Yamete\Driver\LolHentaiPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
