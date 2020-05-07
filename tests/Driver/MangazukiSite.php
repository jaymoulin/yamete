<?php

namespace YameteTests\Driver;


class MangazukiSite extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangazuki.site/manga/lust-geass/';
        $driver = new \Yamete\Driver\MangazukiSite();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(610, count($driver->getDownloadables()));
    }
}
