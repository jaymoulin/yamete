<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class AnimephileCom extends DriverAbstract
{
    private const DOMAIN = 'animephile.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^?]+).html~',
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
        $this->sUrl = "http://www.{$this->getDomain()}/{$this->aMatches['category']}/{$this->aMatches['album']}.html";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oContent = $this->getDomParser()->loadStr((string)$oRes->getBody());
        $aReturn = [];
        $index = 0;
        $oChapters = $oContent->find('.folders a');
        foreach ($oChapters as $oLink) {
            $sChapUrl = $this->sUrl . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sChapUrl);
            $oContent = $this->getDomParser()->loadStr((string)$oRes->getBody());
            foreach ($oContent->find('.thumbs ul li a img') as $oImg) {
                $sFilename = str_replace('/thumbs/', '/', $oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($ndex++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        if (!$index) {
            foreach ($oContent->find('select.selectmpg option') as $oMpg) {
                $oRes = $this->getClient()->request('GET', "{$this->sUrl}?mpg={$oMpg->getAttribute('value')}");
                $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('#mainimage')[0];
                $sFilename = !str_contains($oImg->getAttribute('src'), 'http://')
                    ? "http://www.{$this->getDomain()}{$oImg->getAttribute('src')}"
                    : $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(
            [
                'headers' => ['Cookie' => 'manga_a_warn=1'],
            ]
        );
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
