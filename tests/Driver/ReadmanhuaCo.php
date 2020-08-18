<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ReadmanhuaCo extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://readmanhua.co/manga/koi-lemon/';
        $driver = new \Yamete\Driver\ReadmanhuaCo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(150, count($driver->getDownloadables()));
    }
}
