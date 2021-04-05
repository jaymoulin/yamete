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

class HBrowse extends DriverAbstract
{
    private const DOMAIN = 'hbrowse.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()->request('GET', 'https://www.' . self::DOMAIN . '/' . $this->aMatches['album']);
        $aReturn = [];
        $index = 0;
        $sAccessor = '#main .listTable .listMiddle a';
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($sAccessor) as $oLink) { //chapters
            $sLink = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $oBody = $this->getDomParser()->loadStr((string)$oRes->getBody());
            foreach ($oBody->find('#jsPageList a') as $oImg) { //images
                $sHref = 'https://www.' . self::DOMAIN . $oImg->getAttribute('href') ?: $sLink . '/00001';
                $oRes = $this->getClient()->request('GET', $sHref);
                $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#mangaImage');
                $sFilename = $oImg->getAttribute('src');
                $sFilename = preg_match('~^https?://~', $sFilename)
                    ? $sFilename
                    : 'https://www.' . self::DOMAIN . $sFilename;
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 4, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
