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

class ILikeComixCom extends DriverAbstract
{
    private const DOMAIN = 'ilikecomix.com';
    protected array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download on
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
         * @var Traversable $oPages
         */
        $sUrl = 'https://' .
            implode('/', [$this->getDomain(), $this->aMatches['category'], $this->aMatches['album'], '']);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('figure > a');
        $aReturn = [];
        $index = 0;
        foreach ($oPages as $oLink) {
            $sFilename = $oLink->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
