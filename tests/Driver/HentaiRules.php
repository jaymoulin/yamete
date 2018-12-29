<?php

namespace YameteTests\Driver;


class HentaiRules extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hentairules.net/gal/_2009/kemonono_muchi_to_ha_zai_1-3_and_sub_audio.html';
        $driver = new \Yamete\Driver\HentaiRules();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(76, count($driver->getDownloadables()));
    }
}
