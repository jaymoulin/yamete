<?php

namespace YameteTests\Driver;


class AsmHentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.aaadream.com/thread-469932-1-1.html';
        $driver = new \Yamete\Driver\AAADream();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
