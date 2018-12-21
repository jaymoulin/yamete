<?php

namespace YameteTests\Driver;


class ThreeDCartoonsNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://3dcartoons.net/gals/crazy-xxx-3d-world/118a/';
        $driver = new \Yamete\Driver\ThreeDCartoonsNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
