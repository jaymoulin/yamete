<?php

namespace YameteTests\Driver;


class Hentaifr extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentaifr.net/ngdoujinshishe.php?id=28747&page=1&news=31315';
        $driver = new \Yamete\Driver\Hentaifr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
