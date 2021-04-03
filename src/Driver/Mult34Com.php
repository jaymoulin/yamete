<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Mult34Com extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'mult34.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $aReturn = [];
        $this->sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.gallery-item a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sFilename = $oLink->getAttribute('href');
            if (!$sFilename) {
                continue;
            }
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.thn_post_wrap p img.lazyload') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sFilename = $oLink->getAttribute('data-src');
            if (!$sFilename) {
                continue;
            }
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
