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

class Hentai2read extends DriverAbstract
{
    private const DOMAIN = 'hentai2read.com';
    private array $aMatches = [];
    private array $aReturn = [];
    private int $iPointer = 0;

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
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
        $this->iPointer = 0;
        $this->parse($this->sUrl . '1/');
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @return void
     * @throws GuzzleException
     */
    private function parse(string $sUrl): void
    {
        if (!$sUrl) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        $sAccessor = '~var gData = (?<json>\{[^\}]+\});~';
        $aMatches = [];
        if (preg_match($sAccessor, (string)$oRes->getBody(), $aMatches) == false) {
            return;
        }
        if (!$aMatches['json']) {
            return;
        }
        $aObj = Utils::jsonDecode(str_replace('\'', '"', $aMatches['json']), true);
        foreach ($aObj['images'] as $sPostImage) {
            $sFilename = 'https://static.hentaicdn.com/hentai' . $sPostImage;
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$this->iPointer, 5, '0', STR_PAD_LEFT) .
                '-' . basename($sFilename);
            $this->aReturn[$sPath] = $sFilename;
        }
        $this->parse($aObj['nextURL']);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
