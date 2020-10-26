<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class TMOHentai extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'tmohentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(reader|contents)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(
            [
                'cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $this->sUrl = "https://www.{$this->getDomain()}/contents/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.panel-body .well a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             * @var AbstractNode $oImg
             */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('img.content-image')[0];

            $sFilename = "https://www.{$this->getDomain()}{$oImg->getAttribute('data-original')}";
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
