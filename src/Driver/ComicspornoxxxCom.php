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

class ComicspornoxxxCom extends DriverAbstract
{
    private const DOMAIN = 'comicspornoxxx.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(?<domain>' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            $sLink = $oLink->getAttribute('href');
            $aUrlInfo = parse_url($sLink);
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('#center .text-center img') as $oImg) {
                $sFilename = $aUrlInfo['scheme'] . '://' . $aUrlInfo['host'] . '/' . $oImg->getAttribute('src');
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(
            [
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
