<?php

namespace YameteTests\Driver;


class Doujins extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://doujins.com/hentai-magazine-chapters/herio-addictive-pheromone-36573';
        $driver = new \Yamete\Driver\Doujins();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
