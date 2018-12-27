<?php

namespace YameteTests\Driver;


class TheYiffGallery extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://theyiffgallery.com/index?/category/5770';
        $driver = new \Yamete\Driver\TheYiffGallery();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
