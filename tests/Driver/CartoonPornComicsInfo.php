<?php

namespace YameteTests\Driver;


class CartoonPornComicsInfo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSecure()
    {
        $url = 'https://cartoonporncomics.info/year-1-darkbrain-comics/';
        $driver = new \Yamete\Driver\CartoonPornComicsInfo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(173, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonporncomics.info/camp-sherwood-various-authors/';
        $driver = new \Yamete\Driver\CartoonPornComicsInfo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(202, count($driver->getDownloadables()));
    }
}
