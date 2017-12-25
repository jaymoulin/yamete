<?php

namespace YameteTests\Driver;


class HentaiPorn extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaiporn.pics/fr/galleries/-mikaduki-neko-anata-mo-sister';
        $driver = new \Yamete\Driver\HentaiPorn();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
