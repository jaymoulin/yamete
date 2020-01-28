<?php

namespace YameteTests\Driver;


class LewdManhwaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://lewdmanhwa.com/webtoon/my-girlfriends-sister/chapter/1/';
        $driver = new \Yamete\Driver\LewdManhwaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
