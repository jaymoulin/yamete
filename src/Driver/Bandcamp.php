<?php
namespace SiteDl\Driver;

class Bandcamp extends \SiteDl\DriverAbstract
{
    private $aMatches = [];

    public function canHandle()
    {
        return preg_match('~^https?://(?<artist>.+)\.bandcamp\.com/album/(?<album>.+)$~', $this->sUrl, $this->aMatches);
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        preg_match('~trackinfo: (\[.+?\]),~s', $sBody, $aResult);
        $sFolder = $this->getFolder() . DIRECTORY_SEPARATOR;
        foreach(json_decode($aResult[1], true) as $aContent) {
            $aReturn[$sFolder . $aContent['title'] . '.mp3'] = 'https:' . $aContent['file']['mp3-128'];
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, ['bandcamp.com', $this->aMatches['artist'], $this->aMatches['album']]);
    }
}
