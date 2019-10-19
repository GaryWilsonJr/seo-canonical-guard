<?php
declare(strict_types=1);

namespace Wazum\SeoCanonicalGuard\Seo\Canonical;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Frontend\Utility\CanonicalizationUtility;

/**
 * Class CanonicalGenerator
 *
 * @package Wazum\SeoCanonicalGuard\Seo\Canonical
 * @author Wolfgang Klinger <wolfgang@wazum.com>
 */
class CanonicalGenerator extends \TYPO3\CMS\Seo\Canonical\CanonicalGenerator
{
    /**
     * @return string
     */
    protected function checkDefaultCanonical(): string
    {
        // Returns keys like 'id' or 'cHash'
        $defaultExclude = CanonicalizationUtility::getParamsToExcludeForCanonicalizedUrl(
            (int)$this->typoScriptFrontendController->id,
            (array)$GLOBALS['TYPO3_CONF_VARS']['FE']['additionalCanonicalizedUrlParameters']
        );

        // The current request parameters (flat like 'tx_news_pi1[news]')
        $parameters = array_keys(GeneralUtility::explodeUrl2Array(HttpUtility::buildQueryString($this->getQueryParameters())));

        // Assemble list of allowed parameter namespaces from whitelist and
        // active extension plugins
        $allowed = array_unique(array_merge(
            $this->getWhitelist(),
            $this->shouldIncludePluginNamespaces() ? $this->getAllowedPluginNamespaces() : []
        ));

        $exclude = array_unique(array_merge(
            $defaultExclude,
            array_filter(
                $parameters,
                static function($parameter) use ($allowed) {
                    return !self::isParameterAllowed($parameter, $allowed);
                }
            )
        ));

        return $this->typoScriptFrontendController->cObj->typoLink_URL([
            'parameter' => $this->typoScriptFrontendController->id . ',' . $this->typoScriptFrontendController->type,
            'forceAbsoluteUrl' => true,
            'addQueryString' => true,
            'addQueryString.' => [
                'method' => 'GET',
                'exclude' => implode(',', $exclude)
            ]
        ]);
    }

    /**
     * @return array
     */
    protected function getQueryParameters(): array
    {
        return ($GLOBALS['TYPO3_REQUEST'] instanceof ServerRequestInterface) ?
            $GLOBALS['TYPO3_REQUEST']->getQueryParams() : [];
    }

    /**
     * @return array
     */
    protected function getAllowedPluginNamespaces(): array
    {
        $namespaces = [];
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'] as $extension => $settings) {
            if (!empty($settings['plugins'])) {
                $extension = str_replace(' ', '',
                    ucwords(str_replace('_', ' ', $extension))
                );
                foreach (array_keys($settings['plugins']) as $plugin) {
                    $namespaces[] = '/tx_' . strtolower($extension . '_' . $plugin) . '\[(.*?)\]/';
                }
            }
        }

        return $namespaces;
    }

    /**
     * @return array
     */
    protected function getWhitelist(): array
    {
        $whitelist = [];
        try {
            $list = GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            )->get('seo_canonical_guard', 'whitelist');
            if (!empty($list)) {
                $whitelist = GeneralUtility::trimExplode(',', $list);
            }
        } catch (\Exception $e) {
        }

        return $whitelist;
    }

    /**
     * @return bool
     */
    protected function shouldIncludePluginNamespaces(): bool
    {
        $include = false;
        try {
            $include = (bool)GeneralUtility::makeInstance(
                ExtensionConfiguration::class
            )->get('seo_canonical_guard', 'include_plugin_namespaces');
        } catch (\Exception $e) {
        }

        return $include;
    }

    /**
     * @param string $parameter
     * @param array $allowed
     * @return bool
     */
    protected static function isParameterAllowed(string $parameter, array $allowed): bool
    {
        foreach ($allowed as $rule) {
            // Simple parameter
            if (trim($parameter) === trim($rule)) {
                return true;
            }

            // Create regular expression pattern
            if ($rule[0] !== '/' || $rule[strlen($rule)-1] !== '/') {
                // We assume there's no / in a parameter name, so no escaping
                $rule = "/$rule/";
            }
            if (preg_match($rule, $parameter)) {
                return true;
            }
        }

        return false;
    }
}
