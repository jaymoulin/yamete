<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;
use Yamete\DriverInterface;

class HentaiRead extends DriverAbstract
{
    private const DOMAIN = 'hentairead.com';
    private array $aMatches = [];
    private array $aReturn = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/hentai/(?<album>[^/]+)/$~',
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
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        $index = 0;
        $aMatches = [];
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('li.wp-manga-chapter a') as $oLink) {
            $this->getLinks($oLink->getAttribute('href'));
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match('~var chapter_preloaded_images = ([^]]+])~', (string)$oRes->getBody(), $aMatches)) {
            return;
        }
        $aPages = Utils::jsonDecode($aMatches[1], true);
        foreach ($aPages as $sFilename) {
            $iPos = strpos($sFilename, '?');
            $sFilename = substr($sFilename, 0, $iPos);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function clean(): DriverInterface
    {
        $this->aReturn = [];
        return parent::clean();
    }
}
