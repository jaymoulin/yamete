<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class NhentaiIo extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'nhentai.io';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
         * @var Traversable $oPages
         * @var AbstractNode $oPage
         * @var AbstractNode $oImg
         */
        $sUrl = 'https://' .
            implode('/', [$this->getDomain(), $this->aMatches['album'], 'read', '']);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.reading-content img');
        $aPages = iterator_to_array($oPages);
        krsort($aPages);
        $index = 0;
        $aReturn = [];
        foreach ($aPages as $oPage) {
            $sFilename = trim($oPage->getAttribute('src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
