<?php

namespace YameteTests\Driver;


class HentaiComicsPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaicomics.pro/fr/galleries/milftoon-foam-soap?code=MTcweDF4MTg0MjE=';
        $driver = new \Yamete\Driver\HentaiComicsPro();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
