![logo](logo.png)

Yamete - Hentai downloader in PHP CLI - Easy PHP Site downloader
================================================================

[![latest release](https://img.shields.io/github/release/jaymoulin/yamete.svg "latest release")](http://github.com/jaymoulin/yamete/releases)
[![Docker Pulls](https://img.shields.io/docker/pulls/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Docker stars](https://img.shields.io/docker/stars/jaymoulin/yamete.svg)](https://hub.docker.com/r/jaymoulin/yamete/)
[![Bitcoin donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/btc.png "Bitcoin donation")](https://m.freewallet.org/id/374ad82e/btc)
[![Litecoin donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ltc.png "Litecoin donation")](https://m.freewallet.org/id/374ad82e/ltc)
[![PayPal donation](https://github.com/jaymoulin/jaymoulin.github.io/raw/master/ppl.png "PayPal donation")](https://www.paypal.me/jaymoulin)

This image allows you easily download specific assets of a site

Usage
-----

```
Usage:
  download [options]

Options:
  -u, --url[=URL]          Url to download from
  -l, --list[=LIST]        List file with multiple urls
  -p, --pdf                Optional to create a PDF
  -z, --zip                Optional to create a zip file
  -d, --drivers[=DRIVERS]  Optional array of drivers to add (multiple values allowed)
  -h, --help               Display this help message
  -q, --quiet              Do not output any message
  -V, --version            Display this application version
      --ansi               Force ANSI output
      --no-ansi            Disable ANSI output
  -n, --no-interaction     Do not ask any interactive question
  -v|vv|vvv, --verbose     Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
  Download a URL resources
```

### Mandatory parameter
`-u` or `--url` : the URL to download assets from
OR
`-l` or `--list` : the path to a file containing all URLs to download from on each line

### Optional parameter
 - `-d` or `--drivers`: the path to custom drivers to handle some URL
 - `-p` or `--pdf`: Add this parameter to download a single PDF file instead of multiple images

### Downloads

All assets will be downloaded to the *downloads* folder at the root folder of this project.

Docker
------

You can use Docker image to use this program easily without knowing code or installing PHP etc...

```
docker run --rm -ti -v </path/to/downloads>:/root/downloads jaymoulin/yamete download [...]
```

see usage to complete *\[...\]*

with *\</path/to/downloads>* the path where downloaded assets will be downloaded to.

### Build Docker Image

To build this image locally 

```
docker build -t jaymoulin/yamete -f docker/Dockerfile .
```

Supported sites
---------------

Here's is the list of supported sites for now:

 * asmhentai.com
 * comicspornoxxx.com
 * e-hentai.org
 * 8muses.com
 * 8muses.download
 * freeadultcomix.com
 * hbrowse.com
 * hentai2read.com
 * hentaicomics.pro
 * hentai-comics.org
 * hentaifromhell.org
 * hentai-paradise.fr
 * nhentai.net
 * hentaiporns.net
 * luscious.net
 * myhentaicomics.com
 * nxt-comics.com
 * porncomix.info
 * porncomixonline.net
 * porncomix.site
 * shentai.xyz
 * simply-hentai.com
 * xyzcomics.com
 * hentaifantasy.it
 * hentaicomicsbr.net
 * hentaivn.net
 * hentaimanga.info
 * yaoimangaonline.com
 * milfcomix.com
 * vercomicsporno.com
 * hitomi.la
 * comicsmanics.com
 * hentaifr.net
 * myreadingmanga.info
 * gassummit.ru
 * aaadream.com
 * porncomics.me
 * hentai4manga.com
 * erolord.com
 * pururin.us
 * readhentaimanga.com
 * hmangasearcher.php
 * 3dpornpics.pro
 * cartoonporn.pics
 * hentaipornpics.net
 * hentaiporn.pics
 * mangaporn.pro
 * overwatchporn.pro
 * warcraftporn.pro
 * 3dsexpictures.net
  
You must pass the URL to the album for the program to download it!
