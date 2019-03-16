<?php

namespace YameteTests\Driver;


class ComicsPornNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.comicsporn.net/en/galleries/camp-sherwood-mr-d-ongoing-part-2?code=MTcxeDF4MzIyMzE=#&gid=1&pid=1';
        $driver = new \Yamete\Driver\ComicsPornNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(147, count($driver->getDownloadables()));
    }
}
