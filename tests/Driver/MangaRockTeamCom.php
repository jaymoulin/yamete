<?php

namespace YameteTests\Driver;


class MangaRockTeamCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangarockteam.com/manga/vampire-academy/';
        $driver = new \Yamete\Driver\MangaRockTeamCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(121, count($driver->getDownloadables()));
    }
}
