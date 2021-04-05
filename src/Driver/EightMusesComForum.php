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

class EightMusesComForum extends DriverAbstract
{
    private const DOMAIN = '8muses.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://comics.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/forum/discussion/(?<categ>[^/]+)/(?<album>[^/]+)/?~',
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
        $aReturn = [];
        $this->sUrl = 'https://comics.' . self::DOMAIN . '/forum/discussion/' . $this->aMatches['categ'] . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('img.bbImage') as $oLink) {
            $sFilename = str_starts_with($oLink->getAttribute('src'), 'http')
                ? $oLink->getAttribute('src')
                : 'https://comics.' . self::DOMAIN . $oLink->getAttribute('src');
            if (!$sFilename) {
                continue;
            }
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
