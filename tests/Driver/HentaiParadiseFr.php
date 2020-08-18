<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiParadiseFr extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai-paradise.fr/doujins/sailor-x-vol-3-sailor-x-return-en';
        $driver = new \Yamete\Driver\HentaiParadiseFr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(102, count($driver->getDownloadables()));
    }
}
