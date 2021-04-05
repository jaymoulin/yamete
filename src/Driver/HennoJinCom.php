<?php


namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use iterator;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;
use Yamete\DriverInterface;

class HennoJinCom extends DriverAbstract
{
    private const DOMAIN = 'hennojin.com';
    private array $aMatches = [];
    private bool $bSecondMatch = false;

    public function canHandle(): bool
    {
        $bFistMatch = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/home/manga/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
        if (!$bFistMatch) {
            $this->bSecondMatch = (bool)preg_match(
                '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/home/manga-reader/\?manga=(?<album>[^&]+)~',
                $this->sUrl,
                $this->aMatches
            );
        }
        return $bFistMatch or $this->bSecondMatch;
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
         * @var iterator $oChapters
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

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['User-Agent' => self::USER_AGENT]]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function clean(): DriverInterface
    {
        $this->bSecondMatch = false;
        return parent::clean();
    }
}
