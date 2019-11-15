<?php

namespace YameteTests\Driver;


class HMangaSearcherCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hmangasearcher.com/c/Suyasuya%20Cagliostro/1/2';
        $driver = new \Yamete\Driver\HMangaSearcherCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadChapters()
    {
        $url = 'http://www.hmangasearcher.com/c/Private%20Be@ch/2';
        $driver = new \Yamete\Driver\HMangaSearcherCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(160, count($driver->getDownloadables()));
    }
}
