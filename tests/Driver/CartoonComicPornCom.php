<?php

namespace YameteTests\Driver;


class CartoonComicPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartooncomicporn.com/content/erotikus-kepregenyek/index.html';
        $driver = new \Yamete\Driver\CartoonComicPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
