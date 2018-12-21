<?php

namespace YameteTests\Driver;


class Hentai4Doujin extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentai4doujin.com/hentai_doujin/-Gintama-Silver-Soul---yaoi_27842/';
        $driver = new \Yamete\Driver\Hentai4Doujin();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
