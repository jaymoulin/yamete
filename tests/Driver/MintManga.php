<?php

namespace YameteTests\Driver;


class MintManga extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownloadMulti()
    {
        $url = 'https://mintmanga.live/pink_de_pink';
        $driver = new \Yamete\Driver\MintManga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(170, count($driver->getDownloadables()));
    }
}
