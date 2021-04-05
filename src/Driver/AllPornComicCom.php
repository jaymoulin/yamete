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

class AllPornComicCom extends DriverAbstract
{
    private const DOMAIN = 'allporncomic.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<category>porncomic)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download from
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
         * @var Traversable $oChapters
         */
        $sUrl = 'https://' .
            implode('/', [$this->getDomain(), $this->aMatches['category'], $this->aMatches['album'], '']);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('li.wp-manga-chapter > a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oChapter) {
            $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.wp-manga-chapter-img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('src'));
                $iPos = strpos($sFilename, '?');
                $sFilename = substr($sFilename, 0, $iPos);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
