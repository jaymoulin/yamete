<?php

namespace YameteTests\Driver;


class HentaiHand extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaihand.com/comic/174172/metal-azuki-kurenai-breed';
        $driver = new \Yamete\Driver\HentaiHand();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
