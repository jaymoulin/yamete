<?php

namespace Yamete\Driver;

use AppendIterator;
use ArrayIterator;
use GuzzleHttp\Exception\GuzzleException;
use Iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiWorldFr extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaiworld.fr';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/Doujins/(?<album>[^.]+)/image[0-9]+.html?$~',
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
        $sUrl = "http://" . implode(
                '/',
                ['www.' . $this->getDomain(), 'Doujins', explode('/', $this->aMatches['album'])[0]]
            ) . '/';
        return iterator_to_array($this->getForUrl($sUrl, new AppendIterator()));
    }

    /**
     * @param $sUrl
     * @param $oAppend
     * @param int $iIndex
     * @return Iterator
     * @throws GuzzleException
     */
    private function getForUrl($sUrl, AppendIterator $oAppend, $iIndex = 0): Iterator
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sChapUrl = $oLink->getAttribute('href');
            if ($sChapUrl{0} == '/' || $sChapUrl{0} == '?' || preg_match('~^image[0-9]+\.html?$~', $sChapUrl)) {
                continue;
            }
            $sFilename = $sUrl . $oLink->getAttribute('href');
            if (preg_match('~\.jpe?g$~', $sChapUrl)) {
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$iIndex, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            } elseif ($sChapUrl{-1} == '/') {
                $this->getForUrl($sFilename, $oAppend, $iIndex);
            }
        }
        $oAppend->append(new ArrayIterator($aReturn));
        return $oAppend;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
