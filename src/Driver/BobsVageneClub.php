<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class BobsVageneClub extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'bobsvagene.club';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/])/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.entry-content img') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            if (strpos($sFilename, 'http') !== 0) {
                continue;
            }
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return array_slice($aReturn, 0, -5);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }
}
