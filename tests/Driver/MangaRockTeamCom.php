<?php

namespace YameteTests\Driver;


class MangaRockTeamCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangarockteam.com/manga/sweet-guy/';
        $driver = new \Yamete\Driver\MangaRockTeamCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1822, count($driver->getDownloadables()));
    }
}
