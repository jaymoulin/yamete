<?php

namespace YameteTests\Driver;


class HentaiMangaly extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hentaimangaly.com/the-case-of-my-xxx-loving-wife-who-is-also-my-teacher-chapter-1/';
        $driver = new \Yamete\Driver\HentaiMangaly();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
