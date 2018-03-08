<?php

namespace Yamete\Driver;

class Luscious extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $iCounter = 0;
    const DOMAIN = 'luscious.net';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/albums/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        return $this->download((string)$this->getClient()->request('GET', $this->sUrl)->getBody());
    }

    private function download($sBody, $aReturn = [])
    {
        foreach ($this->getDomParser()->load($sBody)->find('.thumbnail a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load(
                    (string)$this->getClient()
                        ->request('GET', 'https://' . self::DOMAIN . $oLink->getAttribute('href'))
                        ->getBody()
                )
                ->find('#single_picture');
            try {
                $sFilename = $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . str_pad($this->iCounter++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            } catch (\Exception $e) {
                //ignore any kind of error here
            }
        }
        $oPages = $this->getDomParser()->load($sBody)->find('#next_page a');
        if ($oPages && isset($oPages[0])) {
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oPages[0]->getAttribute('href'));
            $aReturn = $this->download((string)$oRes->getBody(), $aReturn);
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
