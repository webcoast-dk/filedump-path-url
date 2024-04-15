# TYPO3 CMS - Nice URLs for protected files

## What is does
This extension converts the `/index.php?eID=dumpFile&t=f&f=23423&token=.....` into a
nice looking URL `/dump-file/f/23423/....`.

Optionally you can append the filename at the end of the URL, like
`/dump-file/f/23423/..../some-protoected-document.pdf`. This allows for more precise
tracking of file downloads with Google Analytics and other similar tools.

## Installation
```shell
composer req webcoast/filedump-path-urls
```

## Configuration
In the extension settings you can enable `Show filename in URL` to append the file
name at the end of the URL.

## FAL Secure Download
This extension has been tested together with `FAL Secure Download`.
