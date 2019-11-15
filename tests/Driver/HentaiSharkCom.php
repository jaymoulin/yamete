<?php

namespace YameteTests\Driver;


class HentaiSharkCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hentaishark.com/manga/toritate-namaniku-fresh-raw-meat';
        $driver = new \Yamete\Driver\HentaiSharkCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
