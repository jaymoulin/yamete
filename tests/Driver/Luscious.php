<?php

namespace YameteTests\Driver;


class Luscious extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://luscious.net/albums/study_301006/';
        $driver = new \Yamete\Driver\Luscious();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
