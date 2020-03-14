<?php

namespace YameteTests\Driver;


class CartoonPornComicsInfo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadSecure()
    {
        $url = 'https://cartoonporncomics.info/bunnie-love-2-between-a-cock-and-a-hard-place-burnt-toast-media-comics/';
        $driver = new \Yamete\Driver\CartoonPornComicsInfo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(32, count($driver->getDownloadables()));
    }
}
