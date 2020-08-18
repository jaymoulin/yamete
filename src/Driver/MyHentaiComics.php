<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MyHentaiComics extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'myhentaicomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/index\.php/(?<album>[^/?]+)~',
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
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.g-item a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             * @var AbstractNode $oImg
             */
            $oImg = $this->getDomParser()
                ->load(
                    (string)$this->getClient()->request('GET', 'http://' . self::DOMAIN . $oLink->getAttribute('href'))
                        ->getBody()
                )
                ->find('.g-resize');
            $sFilename = 'http://' . self::DOMAIN
                . substr($oImg->getAttribute('src'), 0, strpos($oImg->getAttribute('src'), '?'));
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
