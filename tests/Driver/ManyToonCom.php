<?php

namespace YameteTests\Driver;


class ManyToonCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manytoon.com/comic/oh-my-chaozu/';
        $driver = new \Yamete\Driver\ManyToonCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(339, count($driver->getDownloadables()));
    }
}
