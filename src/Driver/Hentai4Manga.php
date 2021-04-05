<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPHtmlParser\Options;
use Psr\Http\Client\ClientExceptionInterface;
use Yamete\DriverAbstract;

if (!class_exists(Hentai4Manga::class)) {
    class Hentai4Manga extends DriverAbstract
    {
        private array $aMatches = [];
        private const DOMAIN = 'hentai4manga.com';

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://(?<domain>(www\.)?' . strtr($this->getDomain(), ['.' => '\.']) .
                ')/(?<category>[^/]+)/(?<album>[^/]+)/~',
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
         * @throws ClientExceptionInterface
         */
        public function getDownloadables(): array
        {
            $sMainUrl = "http://" . implode('/', [
                    ($this->aMatches['domain'] ?? $this->getDomain()),
                    $this->aMatches['category'],
                    $this->aMatches['album'],
                ]);
            $oRes = $this->getClient()->request('GET', $sMainUrl);
            $aReturn = [];
            $iNbPage = count(
                    $this->getDomParser()
                        ->loadStr((string)$oRes->getBody(), (new Options)->setCleanupInput(false))
                        ->find('#page div')
                ) - 2;
            $this->parse($this->sUrl, $aReturn);
            if ($iNbPage > 1) {
                for ($page = 2; $page <= $iNbPage; $page++) {
                    $sUrl = substr($this->sUrl, 0, strlen($sMainUrl));
                    $this->parse("${sUrl}_p$page/", $aReturn);
                }
            }
            return $aReturn;
        }

        /**
         * @param string $sUrl
         * @param array $aReturn
         * @throws ChildNotFoundException
         * @throws CircularException
         * @throws ContentLengthException
         * @throws LogicalException
         * @throws NotLoadedException
         * @throws StrictException
         * @throws ClientExceptionInterface
         */
        private function parse(string $sUrl, array &$aReturn): void
        {
            $sSelector = '#thumblist a';
            foreach ($this->getDomParser()->loadFromUrl($sUrl, (new Options)->setCleanupInput(false))->find($sSelector) as $oLink) {
                $sCurrentImg = 'http://' . $this->aMatches['domain'] . $oLink->getAttribute('href');
                $oImg = $this->getDomParser()
                    ->loadFromUrl($sCurrentImg, (new Options)->setCleanupInput(false))
                    ->find('#innerContent div a img, #view_main div a img')[0];
                $sFilename = 'http://' . $this->aMatches['domain'] . $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR .
                    str_pad(count($aReturn) + 1, 5, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
