<?php

namespace YameteTests\Driver;


class Shentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://shentai.xyz/bayushi-brave-porn/';
        $driver = new \Yamete\Driver\Shentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
