<?php

namespace YameteTests\Driver;


class HentaiThai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentaithai.com/forum/index.php?topic=18234.0';
        $driver = new \Yamete\Driver\HentaiThai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
