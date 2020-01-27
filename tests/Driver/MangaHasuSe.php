<?php

namespace YameteTests\Driver;


class MangaHasuSe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mangahasu.se/mujaki-no-rakuen---parallel-p29049.html';
        $driver = new \Yamete\Driver\MangaHasuSe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22 + 20 + 19 + 19 + 21 + 22 + 21, count($driver->getDownloadables()));
    }
}
