<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class AnimephileCom extends DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private $index = 0;
    private const DOMAIN = 'animephile.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^?]+).html~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode[] $oChapters
         * @var AbstractNode $oMpg
         * @var AbstractNode $oImg
         */
        $this->sUrl = "http://www.{$this->getDomain()}/{$this->aMatches['category']}/{$this->aMatches['album']}.html";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oContent = $this->getDomParser()->load((string)$oRes->getBody());
        $this->aReturn = [];
        $this->index = 0;
        $oChapters = $oContent->find('.folders a');
        foreach ($oChapters as $oLink) {
            $sChapUrl = $this->sUrl . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sChapUrl);
            $oContent = $this->getDomParser()->load((string)$oRes->getBody());
            foreach ($oContent->find('.thumbs ul li a img') as $oImg) {
                $sFilename = str_replace('/thumbs/', '/', $oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $this->aReturn[$sBasename] = $sFilename;
            }
        }
        if (!$this->index) {
            foreach ($oContent->find('select.selectmpg option') as $oMpg) {
                $oRes = $this->getClient()->request('GET', "{$this->sUrl}?mpg={$oMpg->getAttribute('value')}");
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#mainimage')[0];
                $sFilename = strpos($oImg->getAttribute('src'), 'http://') === false
                    ? "http://www.{$this->getDomain()}{$oImg->getAttribute('src')}"
                    : $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $this->aReturn[$sBasename] = $sFilename;
            }
        }
        return $this->aReturn;
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
