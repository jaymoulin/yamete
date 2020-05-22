<?php

namespace YameteTests\Driver;


class CartoonPicsPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpicsporn.com/content/erotic-art-1386/index.html';
        $driver = new \Yamete\Driver\CartoonPicsPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
