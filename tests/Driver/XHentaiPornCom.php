<?php

namespace YameteTests\Driver;


class XHentaiPornCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://x-hentaiporn.com/gallery/fucked-by-monsters-54/index.html';
        $driver = new \Yamete\Driver\XHentaiPornCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
