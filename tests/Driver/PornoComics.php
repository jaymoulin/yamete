<?php

namespace YameteTests\Driver;


class PornoComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://pornocomics.net/all-sex-comics/camp-sherwood.html';
        $driver = new \Yamete\Driver\PornoComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(53, count($driver->getDownloadables()));
    }
}
