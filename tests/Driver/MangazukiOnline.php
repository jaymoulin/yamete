<?php

namespace YameteTests\Driver;


class MangazukiOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangazuki.online/manga/rental-girls/';
        $driver = new \Yamete\Driver\MangazukiOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1300, count($driver->getDownloadables()));
    }
}
