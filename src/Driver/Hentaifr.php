<?php

namespace Yamete\Driver;

class Hentaifr extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifr.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)~',
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
        $oPageList = $this->getDomParser()->load((string)$oRes->getBody())->find('.rl-gallery-item a');
        foreach ($oPageList as $oHref) {
            /** @var \PHPHtmlParser\Dom\AbstractNode $oHref */
            $sFilename = $oHref->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
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
