<?php

namespace YameteTests\Driver;


class HentaiFromHell extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentaifromhell.org/gallery2/[Kamitou%20Masaki]%20The%20Invisible%20Teacher%20Yukino%20Sensei%20[english]%20[Hong_Mei_Ling,%20Altrus].html';
        $driver = new \Yamete\Driver\HentaiFromHell();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
