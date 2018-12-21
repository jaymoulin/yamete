<?php

namespace YameteTests\Driver;


class CartoonPornFr extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpornfr.com/content/jessica-rabbit-set-3/index.html';
        $driver = new \Yamete\Driver\CartoonPornFr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(69, count($driver->getDownloadables()));
    }
}
