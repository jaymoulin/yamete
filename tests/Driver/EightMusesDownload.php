<?php

namespace YameteTests\Driver;


class EightMusesDownload extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://8muses.download/royal-guard-special-training-the-legend-of-zelda-porn-comics-8-muses/';
        $driver = new \Yamete\Driver\EightMusesDownload();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
