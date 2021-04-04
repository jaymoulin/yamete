<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class SuperHQNet extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'superhq.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[0-9]{4}/(?<album>.+).html$~',
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
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.single-post .entry a img') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('data-lazy-src');
            if (!str_contains($sFilename, self::DOMAIN)) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sFilename] = $sBasename;
        }
        return array_flip($aReturn);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
