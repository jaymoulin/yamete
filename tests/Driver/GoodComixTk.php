<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class GoodComixTk extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://goodcomix.tk/wreck-it-ralph/wreck-it-ralph-vanellope-von-schweetz-xxx-porno/';
        $driver = new \Yamete\Driver\GoodComixTk();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
