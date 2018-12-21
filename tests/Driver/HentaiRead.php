<?php

namespace YameteTests\Driver;


class HentaiRead extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentairead.com/ero_pippi/';
        $driver = new \Yamete\Driver\HentaiRead();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(243, count($driver->getDownloadables()));
    }
}
