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

class HentaiKunCom extends DriverAbstract
{
    private const DOMAIN = 'hentaikun.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.'])
            . ')/(?<catgory1>[^/]+)/(?<catgory2>[^/]+)/(?<album>[^/]+)~U',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('a.readchap');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', trim($oLink->getAttribute('href')));
            $oPages = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('.form-control option');
            foreach ($oPages as $oPage) {
                $sOption = $oPage->getAttribute('value');
                if (!str_starts_with($sOption, 'https')) {
                    continue;
                }
                $oRes = $this->getClient()->request('GET', $sOption);
                $oImage = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#con img')[0];
                $sFilename = trim($oImage->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
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
