<?php

namespace YameteTests\Driver;


class XXXMangaPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.xxxmanga.pro/galleries/rance-10-part-2#&gid=1&pid=1';
        $driver = new \Yamete\Driver\XXXMangaPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1195, count($driver->getDownloadables()));
    }
}
