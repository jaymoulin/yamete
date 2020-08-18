<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HMangaSearcherCom extends TestCase
{
    /**
     * @throws GuzzleException
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
     * @throws GuzzleException
     */
    public function testDownloadChapters()
    {
        $url = 'http://www.hmangasearcher.com/c/Nue-chan%20Ni%20Dogeza%20Shiteyarasete%20Morau%20Hon/2';
        $driver = new \Yamete\Driver\HMangaSearcherCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
