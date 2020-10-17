<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ManganeloToday extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://manganelo.today/manga/neta-furi-shitetara-gouin-ecchi-honki-ni-naru-made-daite-ii-webtoon';
        $driver = new \Yamete\Driver\ManganeloToday();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(147, count($driver->getDownloadables()));
    }
}
