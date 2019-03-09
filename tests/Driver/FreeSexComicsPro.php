<?php

namespace YameteTests\Driver;


class FreeSexComicsPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
