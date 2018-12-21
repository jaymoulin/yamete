<?php

namespace YameteTests\Driver;


class Pururin extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://pururin.io/gallery/35297/kyouei-swimming';
        $driver = new \Yamete\Driver\Pururin();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
