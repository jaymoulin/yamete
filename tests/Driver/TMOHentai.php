<?php

namespace YameteTests\Driver;


class TMOHentai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.tmohentai.com/reader/5d2916242a9d1/paginated/1';
        $driver = new \Yamete\Driver\TMOHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
