<?php

namespace YameteTests\Driver;


class XXXMangaPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.xxxmanga.pro/galleries/--2496';
        $driver = new \Yamete\Driver\XXXMangaPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(280, count($driver->getDownloadables()));
    }
}
