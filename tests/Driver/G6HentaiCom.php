<?php

namespace YameteTests\Driver;


class G6HentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://g6hentai.com/galleries/pantsumon-968.html';
        $driver = new \Yamete\Driver\G6HentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(34, count($driver->getDownloadables()));
    }
}
