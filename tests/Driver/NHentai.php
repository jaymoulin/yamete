<?php

namespace YameteTests\Driver;


class NHentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://nhentai.net/g/26020/';
        $driver = new \Yamete\Driver\NHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
