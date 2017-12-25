<?php

namespace YameteTests\Driver;


class HentaiPornPics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaipornpics.net/galleries/i-cum-in-my-sister-and-her-friends-takuji';
        $driver = new \Yamete\Driver\HentaiPornPics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
    }
}
