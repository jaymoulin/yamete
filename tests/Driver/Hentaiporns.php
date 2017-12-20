<?php

namespace YameteTests\Driver;


class Hentaiporns extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentaiporns.net/hm-viva-la-d-va-2-overwatch-korean/';
        $driver = new \Yamete\Driver\Hentaiporns();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
