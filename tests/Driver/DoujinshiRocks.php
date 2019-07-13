<?php

namespace YameteTests\Driver;


class DoujinshiRocks extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.doujinshi.rocks/chuuka-naruto-seitokaichou-mitsuki-student-council-president-mitsuki-english-mangareborn-digital/';
        $driver = new \Yamete\Driver\DoujinshiRocks();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(190, count($driver->getDownloadables()));
    }
}
