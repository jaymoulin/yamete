<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class EightMusesComForum extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownloadFromSummary()
    {
        $url = 'https://comics.8muses.com/forum/discussion/20244/y3df-hope-1/';
        $driver = new \Yamete\Driver\EightMusesComForum();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadFromChapter()
    {
        $url = 'https://comics.8muses.com/forum/discussion/20730/kojima-miu-mothers-care-service-ongoing/';
        $driver = new \Yamete\Driver\EightMusesComForum();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(73, count($driver->getDownloadables()));
    }
}
