<?php

namespace YameteTests\Driver;


class AutoWedding extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://auto-wedding.ru/idolmaster/idol_sister-838/';
        $driver = new \Yamete\Driver\AutoWedding();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23 + 17 + 15 + 19 + 17 + 18 + 16, count($driver->getDownloadables()));
    }
}
