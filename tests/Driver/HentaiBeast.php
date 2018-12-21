<?php

namespace YameteTests\Driver;


class HentaiBeast extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentaibeast.com/index.php?/category/279';
        $driver = new \Yamete\Driver\HentaiBeast();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(165, count($driver->getDownloadables()));
    }
}
