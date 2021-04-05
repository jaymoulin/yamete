<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Traversable;
use Yamete\DriverAbstract;

class Taadd extends DriverAbstract
{
    private const DOMAIN = 'taadd.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/book/(?<album>.+)\.html~U',
            $this->sUrl,
            $this->aMatches
        );
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
        /**
         * @var Traversable $oChapters
         */
        $sUrl = $this->sUrl . (strpos($this->sUrl, '?') ? '&' : '?') . 'waring=1';
        $aMatches = [];
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match_all('~<a href="(/chapter/[^"-]+)"~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aChapters = $aMatches[1];
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $sLink) {
            $oResult = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $sLink);
            $oPages = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('#page option');
            foreach ($oPages as $oPage) {
                $oResult = $this->getClient()->request('GET', $oPage->getAttribute('value'));
                $oImage = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('#comicpic')[0];
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(
            [
                'cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
