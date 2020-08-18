<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Palcomix extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'palcomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()
            ->request('GET', 'http://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/index.html');
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            /** @var AbstractNode $oLink */
            $sLink = 'http://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/' . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                if (strpos($oImg->getAttribute('alt'), 'page') !== 0) {
                    continue;
                }
                $sFilename = 'http://' . self::DOMAIN . '/' . $this->aMatches['album']
                    . str_replace('../', '/', $oImg->getAttribute('src'));
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
