<?php

namespace YameteTests\Driver;


class NSOTennis extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://nsotennis.ru/listmangahentai.html/idolmaster/idol_sister-838/';
        $driver = new \Yamete\Driver\NSOTennis();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23 + 17 + 15 + 19 + 17 + 18 + 16, count($driver->getDownloadables()));
    }
}
