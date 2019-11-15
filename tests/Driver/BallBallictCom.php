<?php

namespace YameteTests\Driver;


class BallBallictCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://ballballict.com/galleries12/index.php?/category/275';
        $driver = new \Yamete\Driver\BallBallictCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(346, count($driver->getDownloadables()));
    }
}
