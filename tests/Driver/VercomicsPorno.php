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

class VercomicsPorno extends TestCase
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
    public function testDownloadFoster()
    {
        $url = 'https://vercomicsporno.com/mac-primera-vez-exclusiva-accel-art';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(13, count($driver->getDownloadables()));
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
    public function testDownloadBowsette()
    {
        $url = 'https://vercomicsporno.com/english-bowsette-rescue-original-vcp';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
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
    public function testDownloadTinkerbell()
    {
        $url = 'https://vercomicsporno.com/milftoon-tinkerfuck-traduccion-exclusiva';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
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
    public function testDownloadRaven()
    {
        $url = 'https://vercomicsporno.com/zillionaire-luck-less-traduccion-exclusiva';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(43, count($driver->getDownloadables()));
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
    public function testDownloadMilf()
    {
        $url = 'https://vercomicsporno.com/dat-milf';
        $driver = new \Yamete\Driver\VercomicsPorno();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(42, count($driver->getDownloadables()));
    }
}
