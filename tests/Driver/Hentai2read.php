<?php

namespace YameteTests\Driver;


class Hentai2read extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentai2read.com/clumsy_girl/';
        $driver = new \Yamete\Driver\Hentai2read();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
