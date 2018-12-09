<?php

namespace YameteTests\Driver;


class XXXHentaiPicsPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxhentaipics.pro/gallery/kill-la-kill-collection-part-10.html';
        $driver = new \Yamete\Driver\XXXHentaiPicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(419, count($driver->getDownloadables()));
    }
}
