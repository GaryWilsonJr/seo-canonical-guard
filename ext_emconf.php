<?php

$EM_CONF['seo_canonical_guard'] = [
    'title' => 'SEO Canonical URL guard',
    'description' => 'Prevent unwanted parameters in canonical URL',
    'category' => 'frontend',
    'author' => 'Wolfgang Klinger',
    'author_email' => 'wolfgang@wazum.com',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author_company' => 'wazum.com',
    'version' => '1.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
            'seo' => '9.5.0-10.4.99'
        ]
    ]
];
