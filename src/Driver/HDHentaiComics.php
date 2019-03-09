<?php

namespace Yamete\Driver;

class HDHentaiComics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hdhentaicomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        $oImgs = $this->getDomParser()->load((string)$oRes->getBody())->find('.my-gallery figure img');
        foreach ($oImgs as $oImg) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */
            $sFilename = strtr($oImg->getAttribute('data-src'), ['/thumbs/' => '/images/']);
            $sFilename{strrpos($sFilename, '-')} = '/';
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
