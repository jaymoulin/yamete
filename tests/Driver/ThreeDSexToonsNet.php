<?php

namespace YameteTests\Driver;


class ThreeDSexToonsNet extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dsextoons.net/gals/crazy-xxx-3d-world/375e/';
        $driver = new \Yamete\Driver\ThreeDSexToonsNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
