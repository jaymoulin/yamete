<?php

namespace YameteTests\Driver;


class CartoonPornFr extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpornfr.com/content/highschool-of-dead/index.html';
        $driver = new \Yamete\Driver\CartoonPornFr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
