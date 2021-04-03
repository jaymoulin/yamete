<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HeavenToonCom extends TestCase
{
    private const NUMBER_OF_PAGE = 1164;

    /**
     * @throws GuzzleException
     */
    public function testDownloadFromSummary()
    {
        $url = 'https://ww4.heaventoon.com/martial-god-asura/';
        $driver = new \Yamete\Driver\HeavenToonCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(self::NUMBER_OF_PAGE, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadFromChapter()
    {
        $url = 'https://ww4.heaventoon.com/martial-god-asura-chap-1/';
        $driver = new \Yamete\Driver\HeavenToonCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(self::NUMBER_OF_PAGE, count($driver->getDownloadables()));
    }
}
