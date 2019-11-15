<?php

namespace YameteTests\Driver;


class EighteenComicOrg extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://18comic.org/photo/100123/';
        $driver = new \Yamete\Driver\EighteenComicOrg();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
