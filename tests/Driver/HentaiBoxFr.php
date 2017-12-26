<?php

namespace YameteTests\Driver;


class HentaiBoxFr extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentaibox.fr/manga/1608-todds-extra-01/lire';
        $driver = new \Yamete\Driver\HentaiBoxFr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
