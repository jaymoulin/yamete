<?php

namespace YameteTests\Driver;


class YuriIsmNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.yuri-ism.net/slide/read/mira/en/12/1/page/1';
        $driver = new \Yamete\Driver\YuriIsmNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1147, count($driver->getDownloadables()));
    }
}
