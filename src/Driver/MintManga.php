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

class MintManga extends DriverAbstract
{
    private const DOMAIN = 'mintmanga.live';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(?<album>[^/]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getDomain(): string
    {
        return self::DOMAIN;
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
        $sBaseUrl = 'http://' . $this->getDomain();
        $oRes = $this->getClient()->request('GET', $sBaseUrl . '/' . $this->aMatches['album'] . '/vol1/1?mtr=1');
        $aReturn = [];
        $index = 0;
        $sSelector = '#chapterSelectorSelect option';
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($sSelector) as $oLink) {
            usleep(1000);
            $sChapterUrl = $sBaseUrl . $oLink->getAttribute('value');
            $oRes = $this->getClient()->request('GET', $sChapterUrl);
            $sRegExp = '~rm_h.init\((?<json>[^\)]+)\)~';
            $aMatches = [];
            if (!preg_match($sRegExp, (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            $sJsonClean = implode(',', array_slice(explode(',', trim($aMatches['json'])), 0, -2));
            foreach (explode('],[', $sJsonClean) as $sString) {
                $sFilename = str_replace(['"', '\''], '', implode('', array_slice(explode(',', $sString), 1, 2)));
                $sFilename = preg_replace('~\?.*$~', '', $sFilename);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
