<?php

function xmldb_block_msmycourses2_upgrade($oldversion = 0): bool {

    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021120702) {

        $table = new xmldb_table('block_msmycourses2_savestate');

        if(!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
            $table->add_field('user', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_field('block', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_field('category', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('alt_view', XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_field('value', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL);
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id'], null, null);
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2021120702, 'block', 'msmycourses2');
    }

    return true;
}
