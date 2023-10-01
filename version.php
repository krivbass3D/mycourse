<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2023090500;
$plugin->requires  = 2020061500;
$plugin->component = 'block_mycourse';

$plugin->dependencies = [
        'local_mscore' => 2023062900
];
