<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Yamete\Driver\XXXComicSexPicturesNet;

class ThreeDSexPicturesNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.3dsexpictures.net/galleries/teacher-azalea-adrian-mccockin';
        $driver = new XXXComicSexPicturesNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
