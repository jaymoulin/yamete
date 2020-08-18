<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class CartoonPicsPornCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpicsporn.com/content/erotic-art-1386/index.html';
        $driver = new \Yamete\Driver\CartoonPicsPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(46, count($driver->getDownloadables()));
    }
}
