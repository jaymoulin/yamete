<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class KissLoveNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://kisslove.net/manga-chikashitsu-dungeon-binbou-kyoudai-wa-goraku-o-motomete-saikyou-e-manga-raw.html';
        $driver = new \Yamete\Driver\KissLoveNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(266, count($driver->getDownloadables()));
    }
}
