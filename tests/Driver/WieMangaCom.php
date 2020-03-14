<?php

namespace YameteTests\Driver;


class WieMangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
