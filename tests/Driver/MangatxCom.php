<?php

namespace YameteTests\Driver;


class MangatxCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangatx.com/manga/infinite-apostles-and-twelve-war-girls/';
        $driver = new \Yamete\Driver\MangatxCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(139, count($driver->getDownloadables()));
    }
}
