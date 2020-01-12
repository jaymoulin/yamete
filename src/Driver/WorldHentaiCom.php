<?php

namespace Yamete\Driver;

class WorldHentaiCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'world-hentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
        /**
         * @var \Traversable $oPages
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $sUrl = implode('/', ['http:/', self::DOMAIN, $this->aMatches['album'],]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('a.highslide');
        $index = 0;
        $aReturn = [];
        $aDeduplicate = [];
        foreach ($oPages as $oLink) {
            $sFilename = $oLink->getAttribute('href');
            if (isset($aDeduplicate[$sFilename])) {
                continue;
            }
            $aDeduplicate[$sFilename] = true;
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return array_merge(array_slice($aReturn, -1, 1), array_slice($aReturn, 0, $index - 1));
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
