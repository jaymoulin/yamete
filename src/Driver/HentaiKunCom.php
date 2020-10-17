<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class HentaiKunCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaikun.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.'])
            . ')/(?<catgory1>[^/]+)/(?<catgory2>[^/]+)/(?<album>[^/]+)~U',
            $this->sUrl,
            $this->aMatches
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

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         * @var AbstractNode[] $oPages
         * @var AbstractNode $oLink
         * @var AbstractNode $oImage
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('a.readchap');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', trim($oLink->getAttribute('href')));
            $oPages = $this->getDomParser()->load((string)$oResult->getBody())->find('.form-control option');
            foreach ($oPages as $oPage) {
                $sOption = $oPage->getAttribute('value');
                if (strpos($sOption, 'https') === false) {
                    continue;
                }
                $oRes = $this->getClient()->request('GET', $sOption);
                $oImage = $this->getDomParser()->load((string)$oRes->getBody())->find('#con img')[0];
                $sFilename = trim($oImage->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
