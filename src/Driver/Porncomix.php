<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Porncomix extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'porncomix.info';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^http://www\.' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
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
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallery dt a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             * @var AbstractNode $oImg
             */
            $oImg = $this->getDomParser()
                ->load((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('.single-post .attachment-image img');
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
