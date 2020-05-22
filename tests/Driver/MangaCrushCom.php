<?php

namespace YameteTests\Driver;


class MangaCrushCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangacrush.com/manga/against-the-gods/';
        $driver = new \Yamete\Driver\MangaCrushCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(150, count($driver->getDownloadables()));
    }

}
