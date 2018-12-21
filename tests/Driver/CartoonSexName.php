<?php

namespace YameteTests\Driver;


class CartoonSexName extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonsex.name/content/toonz-36/index.html';
        $driver = new \Yamete\Driver\CartoonSexName();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1, count($driver->getDownloadables()));
    }
}
