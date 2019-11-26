<?php

namespace YameteTests\Driver;


class MintManga extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mintmanga.live/marshal__tvoia_jena_snova_sbejala';
        $driver = new \Yamete\Driver\MintManga();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(756, count($driver->getDownloadables()));
    }
}
