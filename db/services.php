<?php

    /**
     * Web service for mycourse
     * @package    block_mycourse
     * @subpackage db
     * @copyright  2019 Mastersolution AG
     */

    $functions = [
            'block_mycourse_initial_load' => [
                    'classname'     => 'block_mycourse_external',
                    'methodname'    => 'initial_load',
                    'classpath'     => 'blocks/mycourse/classes/external.php',
                    'description'   => 'initial_load',
                    'type'          => 'read',
                    'ajax'          => true,
            ],
            'block_mycourse_load_category' => [
                    'classname'     => 'block_mycourse_external',
                    'methodname'    => 'load_category',
                    'classpath'     => 'blocks/mycourse/classes/external.php',
                    'description'   => 'Load a category',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_load_menu_category' => [
                    'classname'     => 'block_mycourse_external',
                    'methodname'    => 'load_menu_category',
                    'classpath'     => 'blocks/mycourse/classes/external.php',
                    'description'   => 'menu category',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_show_more' => [
                    'classname'     => 'block_mycourse_external',
                    'methodname'    => 'show_more',
                    'classpath'     => 'blocks/mycourse/classes/external.php',
                    'description'   => 'show more courses',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_alt_view' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'alt_view',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database alt_view savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_menu' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'menu',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database menu savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_page' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'page',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database page savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_compact' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'compact',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database compact savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_collapse' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'collapse',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database collapse savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_mycourse_savestates_slider' => [
                    'classname'     => 'block_mycourse_savestates_external',
                    'methodname'    => 'slider',
                    'classpath'     => 'blocks/mycourse/classes/savestates_external.php',
                    'description'   => 'database slider savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ]
    ];
