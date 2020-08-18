<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class PorncomixOnline extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'porncomixonline.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $bFound = false;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.unite-gallery img') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('data-image');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            $bFound = true;
        }
        if (!$bFound) {
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('figure.dgwt-jg-item a') as $oLink) {
                /**
                 * @var AbstractNode $oLink
                 */
                $sFilename = explode('?', $oLink->getAttribute('href'))[0];
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
