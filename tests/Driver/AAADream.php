<?php

namespace YameteTests\Driver;


class AAADream extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.aaadream.com/thread-469908-1-1.html';
        $driver = new \Yamete\Driver\AAADream();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
