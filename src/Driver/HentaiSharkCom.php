<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPHtmlParser\Options;
use Yamete\DriverAbstract;

class HentaiSharkCom extends DriverAbstract
{
    private const DOMAIN = 'hentaishark.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/?]+)/?~',
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
        $index = 1;
        $oRes = $this->getClient()
            ->request('GET', 'https://www.' . self::DOMAIN . '/manga/' . $this->aMatches['album']);
        $aReturn = [];
        $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody(), (new Options)->setCleanupInput(false));
        foreach ($oParser->find('ul.chapters a') as $oLink) {
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody(), (new Options)->setCleanupInput(false));
            foreach ($oParser->find('#all img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('data-src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
