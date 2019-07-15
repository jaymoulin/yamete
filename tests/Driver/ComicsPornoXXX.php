<?php

namespace YameteTests\Driver;


class ComicsPornoXXX extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://comicsporno.xxx/infinity-war-xxx-marvel-porno-spiderman/';
        $driver = new \Yamete\Driver\ComicsPornoXXX();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
