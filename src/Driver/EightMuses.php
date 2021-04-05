<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class EightMuses extends DriverAbstract
{
    private const DOMAIN = '8muses.com';
    private array $aMatches = [];
    private array $aReturn = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(comics|www)\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/comi(x|cs)/album/(?<album>[^?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        $this->aReturn = [];
        $this->prepareLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function prepareLinks(string $sUrl): void
    {
        $oParser = $this->getDomParser()->loadStr($this->getBody($sUrl));
        foreach ($oParser->find('a.c-tile') as $oLink) {
            $sDomain = self::DOMAIN;
            $sHref = $oLink->getAttribute('href');
            if (!$sHref) {
                continue;
            }
            $sLink = "https://comics.$sDomain$sHref";
            if (!preg_match('~/[0-9]+$~', $sLink)) {
                $this->prepareLinks($sLink);
                continue;
            }
            foreach ($oParser->find('.image img.lazyload') as $oImg) {
                $sFilename = str_replace('/th/', '/fm/', "https://www.$sDomain" . $oImg->getAttribute('data-src'));
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . str_pad(count($this->aReturn) + 1, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $this->aReturn[$sPath] = $sFilename;
            }
            return;
        }
    }

    /**
     * Retrieve body for specified url
     * @param string $sUrl
     * @return string
     * @throws
     */
    private function getBody(string $sUrl): string
    {
        return (string)$this->getClient()->request('GET', $sUrl)->getBody();
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Accept' => '*/*']]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
