<?php

namespace YameteTests\Driver;


class ReadHentaiManga extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://readhentaimanga.com/bitch-hole-english/';
        $driver = new \Yamete\Driver\ReadHentaiManga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
