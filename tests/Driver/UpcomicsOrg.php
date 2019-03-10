<?php

namespace YameteTests\Driver;


class UpcomicsOrg extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://upcomics.org/adult-comics/2248-milftoon-elastic-milf.html';
        $driver = new \Yamete\Driver\UpcomicsOrg();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }
}
