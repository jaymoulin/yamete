<?php

namespace YameteTests\Driver;


class XLecXCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://xlecx.com/7878-no-sunshine.html';
        $driver = new \Yamete\Driver\XLecXCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
