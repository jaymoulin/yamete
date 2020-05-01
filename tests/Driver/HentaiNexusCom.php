<?php

namespace YameteTests\Driver;


class HentaiNexusCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentainexus.com/view/7851';
        $driver = new \Yamete\Driver\HentaiNexusCom;
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
