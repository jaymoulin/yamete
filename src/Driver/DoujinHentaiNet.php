<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class DoujinHentaiNet extends DriverAbstract
{
    private const DOMAIN = 'doujinhentai.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/(?<category>[^/]+)/(?<album>[^/]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        $sUrl = "https://" . $this->getDomain() . '/' .
            implode('/', [$this->aMatches['category'], $this->aMatches['album']]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('ul.version-chap a') as $oLink) {
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('div#all img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('data-src'));
                if (!$sFilename) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
