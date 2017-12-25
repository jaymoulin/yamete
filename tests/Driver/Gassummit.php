<?php

namespace YameteTests\Driver;


class Gassummit extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://gassummit.ru/yu-gi-oh/futanari-school-girl-puishment-6734/';
        $driver = new \Yamete\Driver\Gassummit();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
