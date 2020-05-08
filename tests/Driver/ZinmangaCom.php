<?php

namespace YameteTests\Driver;


class ZinmangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://zinmanga.com/manga/for-love-for-blood/';
        $driver = new \Yamete\Driver\ZinmangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(324, count($driver->getDownloadables()));
    }
}
