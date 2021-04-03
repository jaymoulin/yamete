<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Erofus extends DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private const DOMAIN = 'erofus.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<collection>[^/]+)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws GuzzleException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.row-content a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sHref = $oLink->getAttribute('href');
            if (!$oLink->getAttribute('title')) {
                continue;
            }
            $this->getLinks(strpos($sHref, '://') !== false ? $sHref : 'https://www.' . self::DOMAIN . $sHref);
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        /**
         * @var AbstractNode $oImg
         */
        $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#picture-full img')[0];
        $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
        $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
            . '-' . basename($sFilename);
        $this->aReturn[$sBasename] = $sFilename;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
