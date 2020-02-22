<?php

namespace YameteTests\Driver;


class EliteMangasTop extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://elitemangas.top/manga/14-sai-to-illustrator';
        $driver = new \Yamete\Driver\EliteMangasTop();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(67, count($driver->getDownloadables()));
    }
}
