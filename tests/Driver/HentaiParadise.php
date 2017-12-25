<?php

namespace YameteTests\Driver;


class HentaiParadise extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentai-paradise.fr/doujins/hahaoya-swap-omae-no-kaa-chan-ore-no-mono-4-mother-swap-your-mother-belongs-to-me-4-en';
        $driver = new \Yamete\Driver\HentaiParadise();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(81, count($driver->getDownloadables()));
    }
}
