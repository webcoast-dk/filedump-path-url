<?php

declare(strict_types=1);


namespace WEBcoast\FiledumpPathUrls\EventListener;


use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Resource\Event\GeneratePublicUrlForResourceEvent;
use TYPO3\CMS\Core\Resource\ResourceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class GeneratePublicUrlListener
{
    protected static $isProcessingUrl = false;

    public function __invoke(GeneratePublicUrlForResourceEvent $event)
    {
        $controller = $this->getCurrentFrontendController();
        if (self::$isProcessingUrl || !$controller) {
            return;
        }
        $resource = $event->getResource();
        if (!$this->isLocalResource($resource) || $event->getStorage()->isPublic()) {
            return;
        }

        // Before calling getPublicUrl, we set the static property to true to avoid to be called in a loop
        self::$isProcessingUrl = true;
        try {
            $resource = $event->getResource();
            $originalUrl = $event->getStorage()->getPublicUrl($resource);
            if ($originalUrl) {
                $urlParts = parse_url($originalUrl);
                parse_str($urlParts['query'], $query);
                if (($query['t'] ?? null) && ($query[$query['t']] ?? null) && ($query['token'] ?? null)) {
                    $publicUrl = [
                        'dump-file',
                        $query['t'],
                        $query[$query['t']],
                        $query['token']
                    ];
                    if (GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('filedump_path_urls', 'showFilename')) {
                        $publicUrl[] = $event->getResource()->getName();
                    }
                    $event->setPublicUrl(implode('/', $publicUrl));
                }
            }
        } finally {
            self::$isProcessingUrl = false;
        }
    }

    private function isLocalResource(ResourceInterface $resource): bool
    {
        return $resource->getStorage()->getDriverType() === 'Local';
    }

    private function getCurrentFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'] ?? null;
    }
}
