<?php

namespace YameteTests\Driver;


class ImgBox extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://imgbox.com/g/c8X0aBUWtR';
        $driver = new \Yamete\Driver\ImgBox();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
