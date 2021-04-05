<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Traversable;
use Yamete\DriverAbstract;

class RawdevartCom extends DriverAbstract
{
    private const DOMAIN = 'rawdevart.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/comic/(?<album>[^./]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/comic/' . $this->aMatches['album'] . '/';
        $oResult = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('.list-group-item a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 1;
        foreach ($aChapters as $oLink) {
            $sBody = (string)$this->getClient()
                ->request('GET', 'https://' . self::DOMAIN . $oLink->getAttribute('href'))
                ->getBody();
            $oPages = $this->getDomParser()->loadStr($sBody)->find('#img-container img.img-fluid');
            foreach ($oPages as $oPage) {
                $sFilename = $oPage->getAttribute('data-src');
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
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
