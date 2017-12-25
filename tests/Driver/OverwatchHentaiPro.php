<?php

namespace YameteTests\Driver;


class OverwatchHentaiPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.overwatchhentai.pro/gallery/3d-hardcore-gif';
        $driver = new \Yamete\Driver\OverwatchHentaiPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
