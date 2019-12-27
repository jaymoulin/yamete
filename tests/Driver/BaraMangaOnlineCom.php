<?php

namespace YameteTests\Driver;


class BaraMangaOnlineCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://baramangaonline.com/jpn-kazuhide-ichikawa-%e5%b8%82%e5%b7%9d%e5%92%8c%e7%a7%80-ichikawa-gekibansha-%e5%b8%82%e5%b7%9d%e5%8a%87%e7%89%88%e7%a4%be-manatsu-ni-santa-ga-yattekita-%e7%9c%9f%e5%a4%8f%e3%81%ab/';
        $driver = new \Yamete\Driver\BaraMangaOnlineCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
