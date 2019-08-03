<?php

namespace YameteTests\Driver;


class CartoonPornPictureCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonpornpicture.com/content/hentai-1337/index.html';
        $driver = new \Yamete\Driver\CartoonPornPictureCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(97, count($driver->getDownloadables()));
    }
}
