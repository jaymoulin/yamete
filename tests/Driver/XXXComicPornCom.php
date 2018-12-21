<?php

namespace YameteTests\Driver;


class XXXComicPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxcomicporn.com/galleries/-manic47-joy-ride-robotboy';
        $driver = new \Yamete\Driver\XXXComicPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
