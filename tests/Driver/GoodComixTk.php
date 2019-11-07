<?php

namespace YameteTests\Driver;


class GoodComixTk extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
