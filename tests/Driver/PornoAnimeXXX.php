<?php

namespace YameteTests\Driver;


class PornoAnimeXXX extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.pornoanimexxx.com/sexo-en-la-playa-xxx-comics-porno-milf/';
        $driver = new \Yamete\Driver\PornoAnimeXXX();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(23, count($driver->getDownloadables()));
    }
}
