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

class HentaiIdTv extends DriverAbstract
{
    private const DOMAIN = 'hentai-id.tv';
    private array $aMatches = [];

    /**
     * @return bool
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function canHandle(): bool
    {
        if (preg_match(
            '~^https?://(www\.)?(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . ')/(?<album>[^/]+)/$~',
            $this->sUrl
        )) {
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $oLink = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.mm2 a')[0];
            $aMatch = [];
            if (preg_match('~\?s=(?<url>.+)~', $oLink->getAttribute('href'), $aMatch)) {
                $this->sUrl = $aMatch['url'];
            }
        }
        $sMatch = '~^https?://(www\.)?(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/manga\.php\?id=(?<album>[0-9]+)~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
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
        $sBaseUrl = "https://{$this->getDomain()}/manga.php?id={$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $sBaseUrl);
        $aReturn = [];
        $index = 0;
        $sSelector = '#inlineFormCustomSelect option';
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($sSelector) as $oLink) {
            $sUrl = "$sBaseUrl&p={$oLink->getAttribute('value')}";
            $oRes = $this->getClient()->request('GET', $sUrl);
            $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('img.img-m2')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
