<?php

namespace YameteTests\Driver;


class Sexy3dComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.sexy3dcomix.com/gallery/icstor-incest-story-sister-and-mom-part-8';
        $driver = new \Yamete\Driver\Sexy3dComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
