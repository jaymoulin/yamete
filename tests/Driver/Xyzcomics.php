<?php

namespace YameteTests\Driver;


class Xyzcomics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://xyzcomics.com/fop-breaking-rules-4-sexy-alien-town/';
        $driver = new \Yamete\Driver\Xyzcomics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
