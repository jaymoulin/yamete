<?php

namespace YameteTests\Driver;


class HentaiCorpCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hentai-corp.com/camp-sherwood';
        $driver = new \Yamete\Driver\HentaiCorpCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(12, count($driver->getDownloadables()));
    }
}
