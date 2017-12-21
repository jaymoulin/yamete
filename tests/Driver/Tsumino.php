<?php

namespace YameteTests\Driver;


class Tsumino extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://www.tsumino.com/Book/Info/35693/sweet-attack';
        $driver = new \Yamete\Driver\Tsumino();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
