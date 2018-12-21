<?php

namespace YameteTests\Driver;


class ThreeDSexPicturesNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.3dsexpictures.net/galleries/teacher-azalea-adrian-mccockin';
        $driver = new \Yamete\Driver\XXXComicSexPicturesNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
