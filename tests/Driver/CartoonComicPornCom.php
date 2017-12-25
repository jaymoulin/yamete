<?php

namespace YameteTests\Driver;


class CartoonComicPornCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://cartooncomicporn.com/content/figures-set-24/index.html';
        $driver = new \Yamete\Driver\CartoonComicPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(55, count($driver->getDownloadables()));
    }
}
