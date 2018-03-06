<?php

namespace YameteTests\Driver;


class TheHentaiComics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://thehentaicomics.com/down-south-3/';
        $driver = new \Yamete\Driver\TheHentaiComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(3, count($driver->getDownloadables()));
    }
}
