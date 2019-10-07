<?php

namespace YameteTests\Driver;


class ComicsPornNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.comicsporn.net/en/galleries/kim-vs-kaa-to-coil-a-spy-part-2';
        $driver = new \Yamete\Driver\ComicsPornNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $aResult = $driver->getDownloadables();
        $this->assertEquals(31, count($aResult));
    }
}
