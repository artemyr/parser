<?php

return [
    'active' => 0,
    'show_result' => false,
    'parse_foreach' => 0,
    'phpquery_foreach_selector' => '',
    'phpquery_get_field' => [
        [
            'name' => 'price',
            'selector' => '.info_block p.price',
            'type' => 'text',
            'attr_name' => '',
        ],
        [
            'name' => 'name',
            'type' => 'text',
            'selector' => '.info_block p.name',
            'attr_name' => '',
        ],
        [
            'name' => 'table',
            'type' => 'extend',
            'selector' => '.info_block table:eq(0)',
            'attr_name' => '',
        ],
        [
            'name' => 'images',
            'type' => 'extend',
            'selector' => '.img_block #bigimg',
            'attr_name' => '',
        ],
        [
            'name' => 'specifications',
            'type' => 'extend',
            'selector' => '.addit_desc ul:eq(0)',
            'attr_name' => '',
        ],
    ],
    'urls_to_pase' => [
    ],
    'urls_from_file' => '/output/stage1stoly',
    'domain_to_parse' => 'https://www.mebelaero.ru',
    'timeout' => 2000,
    'guard_key' => 'askngjnas4561sw14gt1sw54gw5sae5568EE1G',
    'need_jquery' => 1,
    'theme' => 'dark',
    'output_file_name' => 'stage2stoly'
];