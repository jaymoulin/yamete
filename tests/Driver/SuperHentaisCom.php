<?php

namespace YameteTests\Driver;


class SuperHentaisCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.superhentais.com/parody-hentai-manga/slutty-misty/6078363';
        $driver = new \Yamete\Driver\SuperHentaisCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(3, count($driver->getDownloadables()));
    }
}
