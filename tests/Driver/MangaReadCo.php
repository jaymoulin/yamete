<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class MangaReadCo extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangaread.co/manga/choppiri-ecchi-na-san-shimai-demo-oyome-san-ni-shitekuremasuka/';
        $driver = new \Yamete\Driver\MangaReadCo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
