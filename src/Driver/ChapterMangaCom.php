<?php

namespace Yamete\Driver;

use GuzzleCloudflare\Middleware;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;

class ChapterMangaCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $cookieJar = null;
    const DOMAIN = 'chaptermanga.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/read-manga-(?<album>.+)~U',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $sLink
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $sRegExp = '~data: {[^m]+manga_slug : \'(?<slug>[^\']+)\',[^m]+manga_id : \'(?<id>[0-9]+)\'~';
        $aMatches = [];
        if (!preg_match($sRegExp, (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        $sRegExp = '~<meta name="csrf-token" content="(?<csrf>[^"]+)">~';
        $aMatchesCsrf = [];
        if (!preg_match($sRegExp, (string)$oResult->getBody(), $aMatchesCsrf)) {
            return [];
        }
        $sChaps = (string) $this->getClient()->request(
                'POST',
                'https://' . self::DOMAIN . '/get-chapter-list',
                [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'X-CSRF-Token' => $aMatchesCsrf['csrf'],
                    ],
                    'form_params' => [
                        'manga_slug' => $aMatches['slug'],
                        'manga_id' => $aMatches['id'],
                    ],
                ]
            )->getBody();
        $sRegExp = '~href="(?<link>[^"]+)"~';
        $aChapters = [];
        if (!preg_match_all($sRegExp, $sChaps, $aChapters)) {
            return [];
        }
        krsort($aChapters['link']);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters['link'] as $sLink) {
            $oResult = $this->getClient()->request('GET', $sLink);
            $oPages = $this->getDomParser()->load((string)$oResult->getBody())->find('.page-chapter img');
            foreach ($oPages as $oPage) {
                $sFilename = $oPage->getAttribute('data-source');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(
            [
                'cookies' => $this->getCookieJar(),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
    }

    /**
     * @return FileCookieJar
     */
    private function getCookieJar() : FileCookieJar
    {
        return $this->cookieJar = $this->cookieJar ?: new FileCookieJar(tempnam('/tmp', __CLASS__));
    }
}
