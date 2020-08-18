<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;
use Yamete\Driver\FurryPornComNet;

class FurryPornPicsNet extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.furrypornpics.net/galleries/jay-naylor-sarah';
        $driver = new FurryPornComNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(2, count($driver->getDownloadables()));
    }
}
