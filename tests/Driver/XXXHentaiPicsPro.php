<?php

namespace YameteTests\Driver;


class XXXHentaiPicsPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxhentaipics.pro/gallery/teaching-spike';
        $driver = new \Yamete\Driver\XXXHentaiPicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
