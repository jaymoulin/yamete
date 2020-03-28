<?php

namespace YameteTests\Driver;


class CartoonSexName extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonsex.name/content/hentai-i-have-squirted-gallons-everywhere-over/index.html';
        $driver = new \Yamete\Driver\CartoonSexName();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(16, count($driver->getDownloadables()));
    }
}
