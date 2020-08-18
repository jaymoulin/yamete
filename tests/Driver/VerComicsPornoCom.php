<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class VerComicsPornoCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://ver-comics-porno.com/un-corazon-de-manzana-palcomix/';
        $driver = new \Yamete\Driver\VerComicsPornoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(43, count($driver->getDownloadables()));
    }
}
