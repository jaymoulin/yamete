<?php

namespace YameteTests\Driver;


class HentaiPorn extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaiporn.pics/fr/galleries/-mikaduki-neko-anata-mo-sister';
        $driver = new \Yamete\Driver\HentaiPornPics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
