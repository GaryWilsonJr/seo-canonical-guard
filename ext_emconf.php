<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'SEO Canonical URL guard',
    'description' => 'Prevent unwanted parameters in canonical URL',
    'category' => 'frontend',
    'author' => 'Wolfgang Klinger',
    'author_email' => 'wolfgang@wazum.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author_company' => 'wazum.com',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
            'seo' => '9.5.0-9.5.99'
        ]
    ]
];
