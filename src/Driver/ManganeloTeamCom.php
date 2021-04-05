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
use Traversable;

class ManganeloTeamCom extends IsekaiScanCom
{
    private const DOMAIN = 'manganeloteam.com';

    protected array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return string
     */
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
        /**
         * @var Traversable $oChapters
         */
        $sUrl = 'https://' . $this->getDomain() . '/manga/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aMatches = [];
        if (!preg_match('~"manga_id":"([0-9]+)"~', (string)$oRes->getBody(), $aMatches)) {
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
            $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
            $aMatches = [];
            if (!preg_match_all('~src="([^"]+)" class="wp-manga-chapter-img~', (string)$oRes->getBody(), $aMatches)) {
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

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }

    /**
     * Where to download
     * @return string
     */
    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }

    /**
     * Rule to get all chapters links
     * @return string
     */
    protected function getChapterRule(): string
    {
        return '.chapter a';
    }

    protected function getRegexp(): string
    {
        return '~data-src="([^"]+)" class="wp-manga~';
    }
}
