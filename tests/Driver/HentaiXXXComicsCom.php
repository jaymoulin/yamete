<?php

namespace YameteTests\Driver;


class HentaiXXXComicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentaixxxcomics.com/gallery/bens-new-experiences-part-2.html';
        $driver = new \Yamete\Driver\HentaiXXXComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
