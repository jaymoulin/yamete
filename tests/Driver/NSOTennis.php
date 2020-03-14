<?php

namespace YameteTests\Driver;


class NSOTennis extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://x.nsotennis.ru/listmangahentai.html/touhou/alice-cream-mk2-2489/';
        $driver = new \Yamete\Driver\NSOTennis();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }
}
