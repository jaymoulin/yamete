<?php

namespace YameteTests\Driver;


class Xyzcomics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://xyzcomics.com/croc-f-o-p-breaking-rules-5/';
        $driver = new \Yamete\Driver\Xyzcomics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
