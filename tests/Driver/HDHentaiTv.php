<?php

namespace YameteTests\Driver;


class HDHentaiTv extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hdhentai.tv/photos/yumehara-nozomi-san-desu/';
        $driver = new \Yamete\Driver\HDHentaiTv();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
