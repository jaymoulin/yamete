<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class XXXHentaiPicsPro extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxhentaipics.pro/gallery/artist-chuuka-naruto-part-18-5610.html?view=MjY4eDZ4MTE3Nzg=';
        $driver = new \Yamete\Driver\XXXHentaiPicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(700, count($driver->getDownloadables()));
    }
}
