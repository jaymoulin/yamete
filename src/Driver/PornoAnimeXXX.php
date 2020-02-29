<?php

namespace Yamete\Driver;

class PornoAnimeXXX extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'pornoanimexxx.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
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
        $index = 0;
        $oImgList = $this->getDomParser()->load((string)$oRes->getBody(), ['cleanupInput' => false])->find('img');
        foreach ($oImgList as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            if (strpos($oImg->getAttribute('class'), 'size-full') === false) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
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
