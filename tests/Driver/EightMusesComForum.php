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

class EightMusesComForum extends TestCase
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
    public function testDownloadFromSummary()
    {
        $url = 'https://comics.8muses.com/forum/discussion/20244/y3df-hope-1/';
        $driver = new \Yamete\Driver\EightMusesComForum();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
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
    public function testDownloadFromChapter()
    {
        $url = 'https://comics.8muses.com/forum/discussion/20730/kojima-miu-mothers-care-service-ongoing/';
        $driver = new \Yamete\Driver\EightMusesComForum();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(131, count($driver->getDownloadables()));
    }
}
