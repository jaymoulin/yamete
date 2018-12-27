<?php

namespace YameteTests\Driver;


class MultPornNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://multporn.net/comics/adult_time';
        $driver = new \Yamete\Driver\MultPornNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(63, count($driver->getDownloadables()));
    }
}
