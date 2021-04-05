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

class ComicsPornNet extends TestCase
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
        $url = 'http://www.comicsporn.net/en/galleries/kim-vs-kaa-to-coil-a-spy-part-2';
        $driver = new \Yamete\Driver\ComicsPornNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $aResult = $driver->getDownloadables();
        $this->assertEquals(31, count($aResult));
    }
}
