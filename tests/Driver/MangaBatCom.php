<?php

namespace YameteTests\Driver;


class MangaBatCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangabat.com/manga/serie-1088911329';
        $driver = new \Yamete\Driver\MangaBatCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(50, count($driver->getDownloadables()));
    }
}
