<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class ILikeComixCom extends DriverAbstract
{
    protected $aMatches = [];
    private const DOMAIN = 'ilikecomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download on
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
         * @var AbstractNode $oImgs
         */
        $sUrl = 'https://' .
            implode('/', [$this->getDomain(), $this->aMatches['category'], $this->aMatches['album'], '']);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('figure > a');
        $aReturn = [];
        $index = 0;
        foreach ($oPages as $oLink) {
            $sFilename = $oLink->getAttribute('href');
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
