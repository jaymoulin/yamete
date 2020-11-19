<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class LoveHeavenNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://loveheaven.net/manga-ningen-dakedo-maougun-shitennou-ni-sodaterareta-ore-wa-maou-no-musume-ni-aisare-shihai-zokusei-no-kennou-o-ataerare-mashita-manga-raw.html';
        $driver = new \Yamete\Driver\LoveHeavenNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(233, count($driver->getDownloadables()));
    }
}
