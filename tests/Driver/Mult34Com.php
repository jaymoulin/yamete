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

class Mult34Com extends TestCase
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
    public function testDownload()
    {
        $url = 'https://mult34.com/so-lucky/';
        $driver = new \Yamete\Driver\Mult34Com();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(2, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function testDownloadMore()
    {
        $url = 'https://mult34.com/slutty-reputation/';
        $driver = new \Yamete\Driver\Mult34Com();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
