<?php

namespace YameteTests\Driver;


class LolPornPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.lolporn.pro/galleries/-aka6-lol-comic-league-of-legends';
        $driver = new \Yamete\Driver\LolPornPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
