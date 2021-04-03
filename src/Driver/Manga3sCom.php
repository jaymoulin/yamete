<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use iterator;
use Yamete\DriverAbstract;

class Manga3sCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'manga3s.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
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
        /**
         * @var iterator $oChapters
         * @var AbstractNode[] $aChapters
         * @var AbstractNode[] $oPages
         */
        $sUrl = 'https://' . $this->getDomain() . '/manga/' . $this->aMatches['album'] . '/';
        $oResult = $this->getClient()->request('GET', $sUrl);
        $aMatches = [];
        if (!preg_match('~"manga_id":"([0-9]+)"~', (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        $sResponse = (string)$this->getClient()
            ->request(
                'POST',
                'https://' . self::DOMAIN . '/wp-admin/admin-ajax.php',
                [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
                    'form_params' => [
                        'action' => 'manga_get_chapters',
                        'manga' => $aMatches[1],
                    ],
                ]
            )->getBody();
        $oChapters = $this->getDomParser()->loadStr($sResponse)->find('.wp-manga-chapter a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $oResult = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
            $aMatches = [];
            if (!preg_match_all('~src="([^"]+)" class="wp-manga-chapter-img~', (string)$oResult->getBody(), $aMatches)) {
                continue;
            }
            foreach ($aMatches[1] as $sFilename) {
                $sFilename = trim($sFilename);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
