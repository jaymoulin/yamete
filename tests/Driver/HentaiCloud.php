<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiCloud extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hentaicloud.com/comic/h-manga/11193/melkor-mancin-sidney-4';
        $driver = new \Yamete\Driver\HentaiCloud();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(36, count($driver->getDownloadables()));
    }
}
