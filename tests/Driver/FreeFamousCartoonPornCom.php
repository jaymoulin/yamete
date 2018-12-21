<?php

namespace YameteTests\Driver;


class FreeFamousCartoonPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://freefamouscartoonporn.com/content/omorashi-ladies-by-munio/index.html';
        $driver = new \Yamete\Driver\FreeFamousCartoonPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
