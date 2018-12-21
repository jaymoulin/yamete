<?php

namespace YameteTests\Driver;


class HentaiRulesNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hentairules.net/galleries4/index.php?/category/533';
        $driver = new \Yamete\Driver\HentaiRulesNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }
}
