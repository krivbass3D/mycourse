<?php

namespace block_mycourse;

defined('MOODLE_INTERNAL') || die();

class helper {

    public static function metadata_installed(): bool {

           $pluginmanager = \core_plugin_manager::instance();

           $is_installed = $pluginmanager->get_plugin_info('local_msmetadata');

        if($is_installed == null) {
            return 0;
        }
        else {
            return 1;
        }
    }

    public static function favorites_installed(): bool {

        $pluginmanager = \core_plugin_manager::instance();
        $is_installed = $pluginmanager->get_plugin_info('block_mssetfavoritecourse');

        if($is_installed == null) {
            return 0;
        }
        else {
            return 1;
        }
    }

    public static function language_installed(): bool {

        $pluginmanager = \core_plugin_manager::instance();
        $is_installed = $pluginmanager->get_plugin_info('availability_language');

        if($is_installed == null) {
            return 0;
        }
        else {
            return 1;
        }
    }

    public static function certificate_installed(): bool {

        $pluginmanager = \core_plugin_manager::instance();
        $is_installed = $pluginmanager->get_plugin_info('mod_mscertificate');

        if($is_installed == null) {
            return 0;
        }
        else {
            return 1;
        }
    }

}
