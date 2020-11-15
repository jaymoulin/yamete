<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ManganeloTeamCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manganeloteam.com/manga/seijo-no-maryoku-wa-bannou-desu/';
        $driver = new \Yamete\Driver\ManganeloTeamCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(842, count($driver->getDownloadables()));
    }
}
