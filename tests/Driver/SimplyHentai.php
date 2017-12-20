<?php

namespace YameteTests\Driver;


class SimplyHentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://original-work.simply-hentai.com/mushikago-infu-hen-ichi-ni';
        $driver = new \Yamete\Driver\SimplyHentai();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(51, count($driver->getDownloadables()));
    }
}
