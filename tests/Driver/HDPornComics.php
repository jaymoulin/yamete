<?php

namespace YameteTests\Driver;


class HDPornComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hdporncomics.com/lustful-spirit-sex-comic/';
        $driver = new \Yamete\Driver\HDPornComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }
}
