<?php

namespace Yamete\Driver;

class HentaiRules extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentairules.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) .
            ')/gal/_[0-9]{4}/(?<gallery>.+)\.html$~',
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
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('center > a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sHref = $oLink->getAttribute('href');
            $aInfo = parse_url($sHref);
            $oImg = $this->getDomParser()->loadFromUrl($sHref)->find('img')[0];
            if (!$oImg) {
                continue;
            }
            $sFilename = $aInfo['scheme'] . '://' . $aInfo['host'] . '/' . $oImg->getAttribute('src');
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
