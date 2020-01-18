<?php

namespace YameteTests\Driver;


class HmghmgXyz extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hmghmg.xyz/ja/g2/312219/';
        $driver = new \Yamete\Driver\HmghmgXyz();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }
}
