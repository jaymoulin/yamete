<?php

namespace YameteTests\Driver;


class ManganeloTeamCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manganeloteam.com/manga/dungeon-reset/';
        $driver = new \Yamete\Driver\ManganeloTeamCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(349, count($driver->getDownloadables()));
    }
}
