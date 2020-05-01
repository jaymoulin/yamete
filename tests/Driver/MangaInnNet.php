<?php

namespace YameteTests\Driver;


class MangaInnNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangainn.net/maou-no-ore-ga-dorei-elf-wo-yome-ni-shitanda-ga-dou-medereba-ii/1/1';
        $driver = new \Yamete\Driver\MangaInnNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(717, count($driver->getDownloadables()));
    }
}
