<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiHand extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaihand.com/en/comic/art-jam-mitsumaro-taneshizume-no-miko-maki-no-san-taiiku-souko-no-nie-english-hennojin-digital';
        $driver = new \Yamete\Driver\HentaiHand();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
