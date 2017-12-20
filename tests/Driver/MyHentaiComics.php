<?php

namespace YameteTests\Driver;


class MyHentaiComics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://myhentaicomics.com/index.php/Glass-Room';
        $driver = new \Yamete\Driver\MyHentaiComics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
