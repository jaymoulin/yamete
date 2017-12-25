<?php

namespace YameteTests\Driver;


class XXXToonComicsCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaimanga.pro/galleries/metalforever-preggo-maya-occult-academy';
        $driver = new \Yamete\Driver\XXXToonComicsCom();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
