# SEO canonical guard

## What does it do?

The TYPO3 core _seo_ extension adds every given parameter from the request to the generated canonical URL.

This extension helps to prevent unwanted parameters in the canonical URL.

### Extension plugin namespaces

By default parameters within the namespaces from active extensions (e.g. `tx_news_pi1`) are allowed.
This still allows parameters not used by these extensions like `tx_news_pi1[wtf]=seo`.
If you need to be very strict, disable the setting and use the explicit whitelist.

### Explicit whitelist

In addition to or as replacement for the allowed namespaces from active extension plugins, you can set a whitelist if you need to allow additional parameters or want to specify exactly which parameters are allowed.
Go to Admin Tools > Settings > Extension Configuration and select _seo_canonical_guard_ and set the allowed parameter namespaces as follows:

    parameter1, parameter2, namespace_xy\[(.*?)\], namespace_yz\[(explicit1|explicit2)\]

You can add a list of single parameters or use regular expressions (escape special characters!).

## Requirements

The extension depends on the TYPO3 core _seo_ extension.

## Installation

Require the latest package:

    composer require wazum/seo-canonical-guard

## Inspiration

The extension is inspired by https://github.com/sourcebroker/urlguard
which definitely should be installed too if you use the `addQueryString` when creating links with TypoScript!

## Say thanks! and support me

You like this extension? Get something for me (surprise! surprise!) from my wishlist on [Amazon](https://smile.amazon.de/hz/wishlist/ls/307SIOOD654GF/) or [help me pay](https://www.paypal.me/wazum) the next pizza or Pho soup (mjam). Thanks a lot!

