<?php

namespace YameteTests\Driver;


class XXXCartoonSexNet extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxcartoonsex.net/galleries/shagbase-arkham-cunts-batman';
        $driver = new \Yamete\Driver\XXXCartoonSexNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
