<?php

namespace YameteTests\Driver;


class XSexComicsCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xsexcomics.com/fr/galleries/jlullaby-waterballoons';
        $driver = new \Yamete\Driver\XSexComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
