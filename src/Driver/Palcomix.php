<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class Palcomix extends DriverAbstract
{
    private const DOMAIN = 'palcomix.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()
            ->request('GET', 'http://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/index.html');
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            $sLink = 'http://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/' . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('img') as $oImg) {
                if (!str_starts_with($oImg->getAttribute('alt'), 'page')) {
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
