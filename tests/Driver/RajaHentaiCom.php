<?php

namespace YameteTests\Driver;


class RajaHentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://rajahentai.com/index/__xtblog_entry/11009815-nami-robin-dan-hancock-xxx?__xtblog_tag=manga+hentai+one+piece&__xtblog_block_id=1#xt_blog';
        $driver = new \Yamete\Driver\RajaHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
