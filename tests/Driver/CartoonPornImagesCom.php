<?php

namespace YameteTests\Driver;


class CartoonPornImagesCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpornimages.com/content/new-lois-griffin-sexy-pics/index.html';
        $driver = new \Yamete\Driver\CartoonPornImagesCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }
}
