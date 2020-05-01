<?php

namespace YameteTests\Driver;


class MangazukiOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangazuki.online/mangas/burakku-gakkou-ni-tsutomete-shimatta-sensei/';
        $driver = new \Yamete\Driver\MangazukiOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(341, count($driver->getDownloadables()));
    }
}
