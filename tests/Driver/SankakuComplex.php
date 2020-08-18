<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SankakuComplex extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.sankakucomplex.com/2018/12/25/akira02s-semen-soaked-art-not-at-all-hard-to-swallow/';
        $driver = new \Yamete\Driver\SankakuComplex();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
