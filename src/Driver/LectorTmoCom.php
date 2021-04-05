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

class LectorTmoCom extends DriverAbstract
{
    private const DOMAIN = 'lectortmo.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/library/([^/]+)/([0-9]+)/(?<album>[^./]+)~',
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
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('.list-group-item a.btn-default');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 1;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $sBody = (string)$oResult->getBody();
            $oPages = $this->getDomParser()->loadStr($sBody)->find('#viewer-pages-select option');
            $aMatches = [];
            preg_match('~copyToClipboard\(\'([^\']+)\'~', $sBody, $aMatches);
            $sUrl = $aMatches[1];
            foreach ($oPages as $oPage) {
                usleep(20);
                $oResult = $this->getClient()->request('GET', $sUrl . '/' . $oPage->getAttribute('value'));
                $oImage = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('#main-container img')[0];
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
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
