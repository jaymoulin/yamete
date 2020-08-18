<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiFox extends TestCase
{
    /**
     * @throws GuzzleException
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
