<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiImgCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentai-img.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/image/(?<album>[^/?]+)/?~',
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
        $this->sUrl = implode('/', ['https://' . self::DOMAIN, 'image', $this->aMatches['album'], 'page/1/']);
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody());
        $iNbPage = 1;
        $iPage = 1;
        $aMatch = [];
        foreach ($oParser->find('#paginator a') as $oLink) {
            /** @var AbstractNode $oLink */
            if (preg_match('~/page/([0-9]+)~', $oLink->getAttribute('href'), $aMatch)) {
                $iNbPage = (int)$aMatch[1];
            }
        }
        do {
            foreach ($oParser->find('.icon-overlay img') as $oImg) {
                /** @var AbstractNode $oImg */
                $sFilename = $oImg->getAttribute('src');
                $sFilename = strpos($sFilename, 'http') !== false ? $sFilename : 'https:' . $sFilename;
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
            $this->sUrl = str_replace('page/' . $iPage, 'page/' . ++$iPage, $this->sUrl);
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody());
        } while ($iPage <= $iNbPage);
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }

}
