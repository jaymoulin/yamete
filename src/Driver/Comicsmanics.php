<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Comicsmanics extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'comicsmanics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.single-post img.alignnone') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            $sFilename = preg_match('~^https?://~', $sFilename)
                ? str_replace('https://', 'http://', $sFilename)
                : 'http://www.' . self::DOMAIN . $sFilename;
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        if (!$index) {
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.size-large') as $oImg) {
                /**
                 * @var AbstractNode $oImg
                 */
                $sFilename = $oImg->getAttribute('src');
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
