<?php

namespace YameteTests\Driver;


class ComicPornoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.comic-porno.com/dibujos-animados/los-padrinos-magicos-xxx-con-vicky-timmy-cosmo-y-wanda/';
        $driver = new \Yamete\Driver\ComicPornoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
