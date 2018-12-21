<?php

namespace YameteTests\Driver;


class FreeSexComixPro extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.freesexcomix.pro/images/what-fuck-atilio-gambedotti';
        $driver = new \Yamete\Driver\FreeSexComixPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
