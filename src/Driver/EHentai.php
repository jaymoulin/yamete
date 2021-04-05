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

class EHentai extends DriverAbstract
{
    private const DOMAIN = 'e-hentai.org';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<mode>[sg])/([^/]+)/(?<album>[^/-]+)~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        if ($this->aMatches['mode'] == 's') {
            $sHref = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#i5 a')[0]->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sHref);
        }
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.gdtm a') as $oLink) {
            $oImg = $this->getDomParser()
                ->loadStr((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('#i3 img');
            $sFilename = $oImg->getAttribute('src');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad($index++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
