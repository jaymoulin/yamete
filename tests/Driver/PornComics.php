<?php

namespace YameteTests\Driver;


class PornComics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.porncomics.me/galleries/yuri-and-friends-full-color-9-1';
        $driver = new \Yamete\Driver\PornComics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
