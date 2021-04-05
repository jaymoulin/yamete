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

class ReadmOrg extends DriverAbstract
{
    private const DOMAIN = 'readm.org';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
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
        $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . "/manga/{$this->aMatches['album']}");
        $aReturn = [];
        $oChapters = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#table-episodes-title a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oChapter->getAttribute('href'));
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.ch-images img') as $oImg) {
                $sSrc = $oImg->getAttribute('src');
                $sFilename = !str_starts_with($sSrc, 'http') ? 'https://' . self::DOMAIN . $sSrc : $sSrc;
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
