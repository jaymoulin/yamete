<?php

namespace YameteTests\Driver;


class ReadmOrg extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://readm.org/manga/2858/1/all-pages';
        $driver = new \Yamete\Driver\ReadmOrg();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
