<?php

namespace YameteTests\Driver;


class ThreeDSexComicsPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dsexcomics.pro/gallery/jab-comix-ay-mami';
        $driver = new \Yamete\Driver\ThreeDSexComicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
