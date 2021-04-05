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

class ReadhentAi extends DriverAbstract
{
    private const DOMAIN = 'readhent.ai';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^?]+)~',
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
        $this->sUrl = "https://{$this->getDomain()}/{$this->aMatches['category']}/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oContent = $this->getDomParser()->loadStr((string)$oRes->getBody());
        $aReturn = [];
        $index = 0;
        $oPages = $oContent->find('.preview_thumb a img');
        foreach ($oPages as $oImg) {
            $sFilename = str_replace('thumb_', '', $oImg->getAttribute('src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
