<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPUnit\Framework\TestCase;

class FreeSexComicsPro extends TestCase
{
    /**
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function testDownloadParams()
    {
        $url = 'http://www.freesexcomics.pro/images/shadbase-short-comics?rel=MjA5eDQ5eDEzMDQ3';
        $driver = new \Yamete\Driver\FreeSexComicsPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
