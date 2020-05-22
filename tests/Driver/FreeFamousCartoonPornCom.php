<?php

namespace YameteTests\Driver;


class FreeFamousCartoonPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://freefamouscartoonporn.com/content/bikini-girls-and-bikini-sex/index.html';
        $driver = new \Yamete\Driver\FreeFamousCartoonPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(58, count($driver->getDownloadables()));
    }
}
