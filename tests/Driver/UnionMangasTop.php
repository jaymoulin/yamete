<?php

namespace YameteTests\Driver;


class UnionMangasTop extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://unionmangas.top/manga/14-sai-to-illustrator';
        $driver = new \Yamete\Driver\UnionMangasTop();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(441, count($driver->getDownloadables()));
    }
}
