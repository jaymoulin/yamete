<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class FreeSexComicsPro extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownloadParams()
    {
        $url = 'http://www.freesexcomics.pro/images/shadbase-short-comics?rel=MjA5eDQ5eDEzMDQ3';
        $driver = new \Yamete\Driver\FreeSexComicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
