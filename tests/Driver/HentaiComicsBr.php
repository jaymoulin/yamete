<?php

namespace YameteTests\Driver;


class HentaiComicsBr extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentaicomicsbr.net/the-milftoon-down-south/';
        $driver = new \Yamete\Driver\HentaiComicsBr();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
