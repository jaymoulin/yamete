<?php

namespace YameteTests\Driver;


class HentaiItaNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai-ita.net/la-figlia-prediletta-1-gallery/';
        $driver = new \Yamete\Driver\HentaiItaNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
