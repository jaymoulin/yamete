<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class SexComicOrg extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'sexcomic.org';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $oImgList = $this->getDomParser()
            ->load((string)$oRes->getBody(), ['cleanupInput' => false])
            ->find('img.alignnone');
        foreach ($oImgList as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFileToDown = $oImg->getAttribute('src');
            $sFileToDown = strpos($sFileToDown, 'https:') !== false ? $sFileToDown : 'https:' . $sFileToDown;
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFileToDown)] = $sFileToDown;
        }
        return $aReturn;
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
