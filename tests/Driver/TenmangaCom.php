<?php

namespace YameteTests\Driver;


class TenmangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.tenmanga.com/book/TENSHI+NO+KYUU.html';
        $driver = new \Yamete\Driver\TenmangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }
}
