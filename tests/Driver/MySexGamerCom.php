<?php

namespace YameteTests\Driver;


class MySexGamerCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mysexgamer.com/doujin/spider-asshole';
        $driver = new \Yamete\Driver\MySexGamerCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
