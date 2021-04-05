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

class Comicsmanics extends DriverAbstract
{
    private const DOMAIN = 'comicsmanics.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
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
        $aReturn = [];
        $index = 0;
        $aRules = [
            '.post-texto img.alignnone',
            '.single-post img.alignnone',
            '.entry-content img.alignnone',
            '.post-texto img.wp-post-image',
            'img.size-large'
        ];
        foreach ($aRules as $sRule) {
            if ($index) {
                continue;
            }
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($sRule) as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sFilename = preg_match('~^https?://~', $sFilename)
                    ? str_replace('https://', 'http://', $sFilename)
                    : 'http://www.' . self::DOMAIN . $sFilename;
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
