<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ManganeloCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manganelo.com/manga/futari_ecchi_for_ladies';
        $driver = new \Yamete\Driver\ManganeloCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(392, count($driver->getDownloadables()));
    }
}
