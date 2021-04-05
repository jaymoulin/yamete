<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class HentaiCloud extends DriverAbstract
{
    private const DOMAIN = 'hentaicloud.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<subCategory>[^/]+)/' .
            '(?<albumId>[^/]+)/(?<album>[^/?]+)/?~',
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
        $iIndex = 0;
        $aReturn = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('div.thumbnail a') as $oLink) {
            $sLink = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.thumbnail img') as $oImg) {
                $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($iIndex++, 5, '0', STR_PAD_LEFT) .
                    basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
