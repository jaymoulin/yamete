<?php

namespace YameteTests\Driver;


class ManhwarawCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manhwaraw.com/manhwa18-raw/breeding-scent/';
        $driver = new \Yamete\Driver\ManhwarawCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
