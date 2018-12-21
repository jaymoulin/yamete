<?php

namespace YameteTests\Driver;


class Chochox extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://chochox.com/billy-and-mandy-milftoon/';
        $driver = new \Yamete\Driver\Chochox();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(18, count($driver->getDownloadables()));
    }
}
