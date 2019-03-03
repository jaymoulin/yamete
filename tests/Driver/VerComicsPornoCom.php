<?php

namespace YameteTests\Driver;


class VerComicsPornoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://ver-comics-porno.com/un-corazon-de-manzana-palcomix/';
        $driver = new \Yamete\Driver\VerComicsPornoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(35, count($driver->getDownloadables()));
    }
}
