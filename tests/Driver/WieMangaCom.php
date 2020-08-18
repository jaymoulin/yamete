<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class WieMangaCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.wiemanga.com/manga/Momo_no_Musume.html';
        $driver = new \Yamete\Driver\WieMangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(88, count($driver->getDownloadables()));
    }
}
