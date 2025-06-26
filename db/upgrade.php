<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file keeps track of upgrades to the block.
 *
 * @package theme_bambuco
 * @copyright 2020 David Herney @ BambuCo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade code for the BambuCo theme.
 *
 * @param int $oldversion
 */
function xmldb_theme_bambuco_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads moodle_database class.

    if ($oldversion < 2025011002.02) {

        // Define new table.
        $table = new xmldb_table('theme_bambuco_subthemes');

        // Adding fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '63', null, XMLDB_NOTNULL, null, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('homeurl', XMLDB_TYPE_CHAR, '511', null, null, null, null);
        $table->add_field('customsettings', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes.
        $table->add_index('idnumber', XMLDB_INDEX_UNIQUE, ['idnumber']);

        // Conditionally launch create the table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2025011002.02, 'theme', 'bambuco');
    }

    if ($oldversion < 2025011003) {

        $select = "plugin = 'theme_bambuco' AND name LIKE 'scss%'";
        $currentconfig = $DB->get_records_select('config_plugins', $select);

        foreach ($currentconfig as $config) {
            $config->name = 'bbco' . $config->name;
            $DB->update_record('config_plugins', $config);
        }

        // Savepoint reached.
        upgrade_plugin_savepoint(true, 2025011003, 'theme', 'bambuco');
    }

    return true;
}
