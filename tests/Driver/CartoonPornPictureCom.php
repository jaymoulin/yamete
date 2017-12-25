<?php

namespace YameteTests\Driver;


class CartoonPornPictureCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://cartoonpornpicture.com/content/into-the-multiverse/index.html';
        $driver = new \Yamete\Driver\CartoonPornPictureCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
