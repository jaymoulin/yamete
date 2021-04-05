<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use iterator;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;


class LoveHugNet extends DriverAbstract
{
    private const DOMAIN = 'lovehug.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[0-9]+)/?~',
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
        /**
         * @var iterator $oChapters
         */
        $sUrl = 'https://' . implode('/', [self::DOMAIN, $this->aMatches['category'], '']);
        $oResult = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('.list-chapters a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $sUrl = 'https://' . self::DOMAIN . $oChapter->getAttribute('href');
            $oResult = $this->getClient()->request('GET', $sUrl);
            $oPages = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('img.chapter-img');
            foreach ($oPages as $oPage) {
                $sFilename = trim($oPage->getAttribute('data-srcset'));
                if (!str_contains($sFilename, 'http')) {
                    $sFilename = base64_decode($sFilename);
                }
                if (!str_contains($sFilename, 'http')) {
                    continue;
                }
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
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['category']]);
    }
}
