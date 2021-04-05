<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Traversable;
use Yamete\DriverAbstract;

class HentaiKai extends DriverAbstract
{
    private const DOMAIN = 'hentaikai.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapter = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.post-fotos a')[0];
        $index = 0;
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
        $sBody = (string)$oRes->getBody();
        $sRegExp = '~body:after{content:([^;]+);display:none}~';
        $aFound = [];
        if (!preg_match($sRegExp, $sBody, $aFound)) {
            return [];
        }
        $aMatches = [];
        if (!preg_match_all('~url\(([^)]+)\)~', $aFound[1], $aMatches)) {
            return [];
        }
        foreach ($aMatches[1] as $sFilename) {
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
