<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ReadmanhuaOnline extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.readmanhua.online/comic/tonikaku-kawaii';
        $driver = new \Yamete\Driver\ReadmanhuaOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1196, count($driver->getDownloadables()));
    }
}
