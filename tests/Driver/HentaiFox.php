<?php

namespace YameteTests\Driver;


class HentaiFox extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaifox.com/gallery/33004/';
        $driver = new \Yamete\Driver\HentaiFox();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(190, count($driver->getDownloadables()));
    }
}
