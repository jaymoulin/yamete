<?php

namespace YameteTests\Driver;


class AsmHentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://asmhentai.com/g/204673/';
        $driver = new \Yamete\Driver\AsmHentai();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
