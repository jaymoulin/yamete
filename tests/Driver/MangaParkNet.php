<?php

namespace YameteTests\Driver;


class MangaParkNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangapark.net/manga/a-crazy-elf-and-a-serious-orc';
        $driver = new \Yamete\Driver\MangaParkNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(165, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadMultiVersion()
    {
        $url = 'https://mangapark.net/manga/kami-sama-x-ore-sama-x-danna-sama';
        $driver = new \Yamete\Driver\MangaParkNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1885, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadMature()
    {
        $url = 'https://mangapark.net/manga/fella-hame-lips';
        $driver = new \Yamete\Driver\MangaParkNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10 + 24 + 28 + 32 + 30 + 36 + 16 + 34 + 12, count($driver->getDownloadables()));
    }
}
