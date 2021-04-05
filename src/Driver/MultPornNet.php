<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class MultPornNet extends DriverAbstract
{
    const DOMAIN = 'multporn.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/[^/]+/(?<album>[^/]+)/?$~',
            $this->sUrl,
            $this->aMatches
        );
    }

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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        $index = 0;
        $sRegExp = '~{"configUrl":"([^"]+)"~';
        if (!preg_match($sRegExp, $sBody, $aMatch)) {
            return [];
        }
        $sUrl = 'https://' . $this->getDomain() . str_replace('\/', '/', $aMatch[1]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('image') as $oImg) {
            $sFilename = preg_replace('~\?.*$~', '', $oImg->getAttribute('largeimageurl'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
