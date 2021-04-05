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

class Comicsmanics extends TestCase
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
        $url = 'http://www.comicsmanics.com/bad-boss-3-y3df/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(79, count($driver->getDownloadables()));
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
    public function testNewFormatDownload()
    {
        $url = 'http://www.comicsmanics.com/milftoon-lemonade-01-incest-comix/';
        $driver = new \Yamete\Driver\Comicsmanics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
