<?php

namespace YameteTests\Driver;


class Myreadingmanga extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://myreadingmanga.info/p-sakai-ringo-house-sitting-eng/';
        $driver = new \Yamete\Driver\Myreadingmanga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(29, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadDiffer()
    {
        $url = 'https://myreadingmanga.info/cannabis-shimaji-katekyo-chuu-namaiki-na-shota-o-oshioki-shita-kekka-erokawaii-ken-ww-eng/';
        $driver = new \Yamete\Driver\Myreadingmanga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
