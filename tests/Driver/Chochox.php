<?php

namespace YameteTests\Driver;


class Chochox extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://chochox.com/neekos-help-lol-hentai/';
        $driver = new \Yamete\Driver\Chochox();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
