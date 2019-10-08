<?php

namespace YameteTests\Driver;


class MintManga extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://mintmanga.com/very_pure';
        $driver = new \Yamete\Driver\MintManga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(808, count($driver->getDownloadables()));
    }
}
