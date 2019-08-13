<?php

namespace Yamete\Driver;

use Yamete\DriverInterface;

class TheDoujinCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private $index = 0;
    const DOMAIN = 'thedoujin.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/index\.php/categories/(?<album>[0-9]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/index.php/categories/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $this->aReturn = [];
        $this->index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('li.page a') as $oChapter) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oChapter */
            $sUrl = 'https://' . self::DOMAIN . $oChapter->getAttribute('href');
            $this->getForUrl($sUrl);
        }
        if (!$this->index) {
            $this->getForUrl($sUrl);
        }
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getForUrl(string $sUrl): void
    {
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oPage
         * @var \PHPHtmlParser\Dom\AbstractNode $oImage
         */
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.post-preview a') as $oPage) {
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oPage->getAttribute('href'));
            $oImage = $this->getDomParser()->load((string)$oRes->getBody())->find('#image')[0];
            $sFilename = $oImage->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$this->index, 5, '0', STR_PAD_LEFT)
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
        $this->index = 0;
        $this->aReturn = [];
        return parent::clean();
    }
}
