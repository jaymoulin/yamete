<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MintManga extends TestCase
{
    /**
     * @throws GuzzleException
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
