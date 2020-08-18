<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiWorldFr extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentaiworld.fr/Doujins/Slut%20Girl/Slut%20Girl%202/image1.htm';
        $driver = new \Yamete\Driver\HentaiWorldFr();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(112, count($driver->getDownloadables()));
    }
}
