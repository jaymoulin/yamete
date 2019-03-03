<?php

namespace YameteTests\Driver;


class HQDeSexo extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hqdesexo.com/policial-tarada-da-noite.html';
        $driver = new \Yamete\Driver\HQDeSexo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
