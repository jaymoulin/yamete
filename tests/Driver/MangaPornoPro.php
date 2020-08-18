<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaPornoPro extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.mangaporno.pro/galleries/mama-was-too-divine-hentai-part-3?code=MTMxeDMyeDUyMjc=#&gid=1&pid=1';
        $driver = new \Yamete\Driver\MangaPornoPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(37, count($driver->getDownloadables()));
    }
}
