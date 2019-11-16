<?php

namespace YameteTests\Driver;


class ACGXMangaComCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.acgxmanga.com/h/45101.html';
        $driver = new \Yamete\Driver\ACGXMangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
