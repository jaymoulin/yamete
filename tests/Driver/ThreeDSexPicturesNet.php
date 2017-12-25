<?php

namespace YameteTests\Driver;


class ThreeDSexPicturesNet extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.3dsexpictures.net/galleries/teacher-azalea-adrian-mccockin';
        $driver = new \Yamete\Driver\ThreeDSexPicturesNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
