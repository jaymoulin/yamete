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
use Yamete\DriverAbstract;

class NinemangaCom extends DriverAbstract
{
    private const DOMAIN = 'ninemanga.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://.{2,3}\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>.+)\.html~U',
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
         * @var Traversable $oChapters
         */
        $sUrl = $this->sUrl . (strpos($this->sUrl, '?') ? '&' : '?') . 'waring=1';
        $oResult = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('a.chapter_list_a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $oPages = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('#page option');
            $iNbPages = count($oPages) / 2;
            $iCurrentPage = 1;
            foreach ($oPages as $oPage) {
                $oResult = $this->getClient()
                    ->request('GET', 'http://www.' . self::DOMAIN . $oPage->getAttribute('value'));
                $oImage = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('img.manga_pic')[0];
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
                if (++$iCurrentPage > $iNbPages) {
                    break;
                }
            }
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(
            [
                'headers' => ['User-Agent' => self::USER_AGENT, 'Accept-Language' => 'en-US;q=0.8,en;q=0.7'],
            ]
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
}
