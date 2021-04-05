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

class MangaOwlCom extends DriverAbstract
{
    private const DOMAIN = 'mangaowl.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/reader/(?<album>[^/?]+)/(?<reader>[^/?]+)~',
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
        $index = 1;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('#selectChapter option') as $oOption) {
            $sUrl = trim($oOption->getAttribute('url'));
            $sUrl = !str_contains($sUrl, '://') ? 'https://' . self::DOMAIN . $sUrl : $sUrl;
            $oRes = $this->getClient()->request('GET', $sUrl);
            $aChap = [];
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('img.owl-lazy') as $oImg) {
                $sFilename = $oImg->getAttribute('data-src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aChap[$sBasename] = $sFilename;
            }
            $aChap = array_reverse($aChap);
            $aReturn = array_merge($aReturn, $aChap);
        }
        return array_reverse($aReturn);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
