<?php


namespace Yamete\Driver;

use GuzzleHttp\Client;
use iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;
use Yamete\DriverInterface;

class HennoJinCom extends DriverAbstract
{
    private $aMatches = [];
    private $bSecondMatch = false;

    private const DOMAIN = 'hennojin.com';

    public function canHandle(): bool
    {
        $bFistMatch = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/home/manga/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
        $this->bSecondMatch = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/home/manga-reader/\?manga=(?<album>[^&]+)~',
            $this->sUrl,
            $this->aMatches
        );
        return $bFistMatch or $this->bSecondMatch;
    }

    public function getDownloadables(): array
    {
        /**
         * @var iterator $oChapters
         * @var AbstractNode $oUrl
         * @var AbstractNode[] $oPages
         */
        $aReturn = [];
        $index = 0;
        if (!$this->bSecondMatch) {
            $sResponse = file_get_contents($this->sUrl);
            $oUrl = $this->getDomParser()->loadStr($sResponse)->find('.col-lg-12 a.btn-primary')[0];
            $this->sUrl = 'https://' . self::DOMAIN . $oUrl->getAttribute('href');
        }
        $oResponse = $this->getClient()->get($this->sUrl);
        $oPages = $this->getDomParser()->loadStr((string)$oResponse->getBody())->find('.mySlides img');
        foreach ($oPages as $oImage) {
            $sFilename = 'https://' . self::DOMAIN . $oImage->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }

    public function clean(): DriverInterface
    {
        $this->bSecondMatch = false;
        return parent::clean();
    }
}
