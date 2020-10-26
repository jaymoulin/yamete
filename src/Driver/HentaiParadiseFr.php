<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiParadiseFr extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentai-paradise.fr';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . ')/doujins/(?<album>[^/]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/doujins/' . $this->aMatches['album'] . '/0';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.lazy') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = str_replace('/thumbs/', '/', $oImg->getAttribute('data-src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
