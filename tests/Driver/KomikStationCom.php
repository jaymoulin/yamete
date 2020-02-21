<?php

namespace YameteTests\Driver;


class KomikStationCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.komikstation.com/manga/2-5-dimensional-seduction/';
        $driver = new \Yamete\Driver\KomikStationCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(88, count($driver->getDownloadables()));
    }
}
