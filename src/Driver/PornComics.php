<?php

namespace Yamete\Driver;

use \Tuna\CloudflareMiddleware;

class PornComics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'porncomics.me';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/galleries/(?<album>[^/?]+)[/?]?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
        $aReturn = [];
        $iCount = count($this->getDomParser()->load((string)$oRes->getBody())->find('.portfolio-normal-width img'));
        for ($i = 1; $i <= $iCount; $i++) {
            foreach ($this->getDomParser()->loadFromUrl($this->sUrl . $i . '/')->find('.main-img a') as $oLink) {
                /**
                 * @var \DOMElement $oLink
                 */
                $sFilename = $oLink->getAttribute('href');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
