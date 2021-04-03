<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ImagearnCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'imagearn.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/gallery\.php\?id=(?<album>[0-9]+)~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody());
        $iNbPage = 1;
        $iPage = 1;
        $aMatch = [];
        foreach ($oParser->find('#gallery a img') as $oImg) {
            /** @var AbstractNode $oImg */
            $sFilename = strtr($oImg->getAttribute('src'), ['https:' => 'http:', '.com/' => '.com/imags/']);
            $sFilename = strpos($sFilename, 'http') !== false ? $sFilename : 'https:' . $sFilename;
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }

}
