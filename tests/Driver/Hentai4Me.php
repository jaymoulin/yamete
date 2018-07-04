<?php

namespace YameteTests\Driver;


class Hentai4Me extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://hentai4me.net/c87-popinrabbit-esora-koto-hoshi-no-shizuku-no-milky-way-shizuku-of-the-stars-milky-way-english-arkngthand-b-e-c-scans.html';
        $driver = new \Yamete\Driver\Hentai4Me();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
