<?php

namespace YameteTests\Driver;


class ComicspornoxxxCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://comicspornoxxx.com/kaa-san-koibito-seikatsu-2/';
        $driver = new \Yamete\Driver\ComicspornoxxxCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
