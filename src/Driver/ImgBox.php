<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ImgBox extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'imgbox.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/g/(?<album>.+)~',
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
        $index = 0;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('#gallery-view-content a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sLink = 'https://' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $sRegexp = '~<img .*id="img" .*src="(?<file>[^"]+)"~u';
            $aMatches = [];
            if (!preg_match($sRegexp, (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $aMatches['file'];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
