<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Comicsmanics extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.comicsmanics.com/bad-boss-3-y3df/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(79, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testNewFormatDownload()
    {
        $url = 'http://www.comicsmanics.com/milftoon-lemonade-01-incest-comix/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
