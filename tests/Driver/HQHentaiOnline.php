<?php

namespace YameteTests\Driver;


class HQHentaiOnline extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hqhentai.online/alistar-fodendo-sona/';
        $driver = new \Yamete\Driver\HQHentaiOnline();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
