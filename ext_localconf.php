<?php

defined('TYPO3_MODE') or die();

(static function () {
    foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags'] as $key => $generator) {
        // Replace the core class here
        if ($generator === \TYPO3\CMS\Seo\Canonical\CanonicalGenerator::class . '->generate') {
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['TYPO3\CMS\Frontend\Page\PageGenerator']['generateMetaTags'][$key] = \Wazum\SeoCanonicalGuard\Seo\Canonical\CanonicalGenerator::class . '->generate';
        }
    }
})();
