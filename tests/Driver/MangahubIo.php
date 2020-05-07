<?php

namespace YameteTests\Driver;


class MangahubIo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangahub.io/manga/libidors';
        $driver = new \Yamete\Driver\MangahubIo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(81, count($driver->getDownloadables()));
    }
}
