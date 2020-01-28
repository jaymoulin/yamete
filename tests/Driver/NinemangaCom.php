<?php

namespace YameteTests\Driver;


class NinemangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.ninemanga.com/manga/LOVE+LIVE%21+DJ+-+PRIVATE+TSUNDERATION.html';
        $driver = new \Yamete\Driver\NinemangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(116, count($driver->getDownloadables()));
    }
}
