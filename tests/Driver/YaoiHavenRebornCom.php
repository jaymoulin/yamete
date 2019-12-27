<?php

namespace YameteTests\Driver;


class YaoiHavenRebornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.yaoihavenreborn.com/doujinshi/tails-secret-hobby-english';
        $driver = new \Yamete\Driver\YaoiHavenRebornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(53, count($driver->getDownloadables()));
    }
}
