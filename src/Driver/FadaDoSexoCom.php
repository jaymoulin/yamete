<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class FadaDoSexoCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'fadadosexo.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download from
     * @return string
     */
    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode $oPages
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('#conteudo a img.lazy');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oPage) {
            $sFilename = trim($oPage->getAttribute('data-src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return array_slice($aReturn, 0, count($aReturn) - 2);
    }

    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
