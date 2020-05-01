<?php

namespace Yamete\Driver;

use \GuzzleCloudflare\Middleware;

class MangazukiMe extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangazuki.me';

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(?<domain>(www\.)?' . strtr($this->getDomain(), ['.' => '\.'])
            . ')/(?<cat>mangas?)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $this->sUrl = "https://{$this->aMatches['domain']}/{$this->aMatches['cat']}/{$this->aMatches['album']}/";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.wp-manga-chapter a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            $sHref = $oLink->getAttribute('href');
            $sHref .= strpos($sHref, "?") !== false ? '' : '?style=list';
            $oRes = $this->getClient()->request('GET', $sHref);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.wp-manga-chapter-img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        $oClient = parent::getClient();
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->remove('cloudflare');
        $oHandler->push(Middleware::create(), 'cloudflare');
        return $oClient;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
