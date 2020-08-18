<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HDPornComics extends TestCase
{
    /**
     * @throws GuzzleException
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
