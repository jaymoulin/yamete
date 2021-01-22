<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SeuhentaiCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.seuhentai.com/putaria-em-meu-malvado-favorito/';
        $driver = new \Yamete\Driver\SeuhentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
