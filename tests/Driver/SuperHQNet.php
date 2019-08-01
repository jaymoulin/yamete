<?php

namespace YameteTests\Driver;


class SuperHQNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.superhq.net/2019/incest-candy-3.html';
        $driver = new \Yamete\Driver\SuperHQNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
