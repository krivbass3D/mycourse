<?php

    /**
     * Web service for msmycourses2
     * @package    block_msmycourses2
     * @subpackage db
     * @copyright  2019 Mastersolution AG
     */

    $functions = [
            'block_msmycourses2_initial_load' => [
                    'classname'     => 'block_msmycourses2_external',
                    'methodname'    => 'initial_load',
                    'classpath'     => 'blocks/msmycourses2/classes/external.php',
                    'description'   => 'initial_load',
                    'type'          => 'read',
                    'ajax'          => true,
            ],
            'block_msmycourses2_load_category' => [
                    'classname'     => 'block_msmycourses2_external',
                    'methodname'    => 'load_category',
                    'classpath'     => 'blocks/msmycourses2/classes/external.php',
                    'description'   => 'Load a category',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_load_menu_category' => [
                    'classname'     => 'block_msmycourses2_external',
                    'methodname'    => 'load_menu_category',
                    'classpath'     => 'blocks/msmycourses2/classes/external.php',
                    'description'   => 'menu category',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_show_more' => [
                    'classname'     => 'block_msmycourses2_external',
                    'methodname'    => 'show_more',
                    'classpath'     => 'blocks/msmycourses2/classes/external.php',
                    'description'   => 'show more courses',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_alt_view' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'alt_view',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database alt_view savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_menu' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'menu',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database menu savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_page' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'page',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database page savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_compact' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'compact',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database compact savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_collapse' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'collapse',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database collapse savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ],

            'block_msmycourses2_savestates_slider' => [
                    'classname'     => 'block_msmycourses2_savestates_external',
                    'methodname'    => 'slider',
                    'classpath'     => 'blocks/msmycourses2/classes/savestates_external.php',
                    'description'   => 'database slider savestate',
                    'type'          => 'write',
                    'ajax'          => true,

            ]
    ];
