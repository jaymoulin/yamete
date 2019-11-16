<?php

namespace YameteTests\Driver;


class DuppNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://d-upp.net/g/8110/#1-';
        $driver = new \Yamete\Driver\DuppNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(36, count($driver->getDownloadables()));
    }
}
