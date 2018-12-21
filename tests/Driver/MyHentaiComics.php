<?php

namespace YameteTests\Driver;


class MyHentaiComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://myhentaicomics.com/index.php/Glass-Room';
        $driver = new \Yamete\Driver\MyHentaiComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
