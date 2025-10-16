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
 * Theme global settings.
 *
 * @package   theme_bambuco
 * @copyright 2023 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_bambuco\local\utils;

defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_bambuco_general', get_string('generalsettings', 'theme_bambuco'));

if ($ADMIN->fulltree) {
    $subtheme = utils::settingup_subtheme();
    $subthemekey = empty($subtheme) ? '' : '_' . $subtheme->id;

    if (utils::iscustomizable_subtheme('unaddableblocks', $subtheme)) {
        // Unaddable blocks.
        // Blocks to be excluded when this theme is enabled in the "Add a block" list: Administration, Navigation, Courses and
        // Section links.
        $default = 'navigation,settings,course_list,section_links';
        $setting = new admin_setting_configtext(
            'theme_bambuco/unaddableblocks' . $subthemekey,
            get_string('unaddableblocks', 'theme_bambuco'),
            get_string('unaddableblocks_desc', 'theme_bambuco'),
            $default,
            PARAM_TEXT
        );
        $page->add($setting);
    }

    if (utils::iscustomizable_subtheme('preset', $subtheme)) {
        // Preset.
        $name = 'theme_bambuco/preset' . $subthemekey;
        $title = get_string('preset', 'theme_bambuco');
        $description = get_string('preset_desc', 'theme_bambuco');
        $default = 'default.scss';

        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'theme_bambuco', 'preset', 0, 'itemid, filepath, filename', false);

        $choices = [];
        foreach ($files as $file) {
            $choices[$file->get_filename()] = $file->get_filename();
        }
        // These are the built in presets.
        $choices['default.scss'] = 'default.scss';
        $choices['plain.scss'] = 'plain.scss';
        $choices['abaco.scss'] = 'Ábaco';
        $choices['fluido.scss'] = 'Fluido';

        $setting = new admin_setting_configthemepreset($name, $title, $description, $default, $choices, 'bambuco');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    if (utils::iscustomizable_subtheme('presetfiles', $subtheme)) {
        // Preset files setting.
        // Never changed for subthemes.
        $name = 'theme_bambuco/presetfiles';
        $title = get_string('presetfiles', 'theme_bambuco');
        $description = get_string('presetfiles_desc', 'theme_bambuco');

        $setting = new admin_setting_configstoredfile(
            $name,
            $title,
            $description,
            'preset',
            0,
            ['maxfiles' => 20, 'accepted_types' => ['.scss']]
        );
        $page->add($setting);
    }

    if (utils::iscustomizable_subtheme('backgroundimage', $subtheme)) {
        // Background image setting.
        $name = 'theme_bambuco/backgroundimage' . $subthemekey;
        $title = get_string('backgroundimage', 'theme_bambuco');
        $description = get_string('backgroundimage_desc', 'theme_bambuco');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'backgroundimage' . $subthemekey);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    if (utils::iscustomizable_subtheme('brandcolor', $subtheme)) {
        // We use an empty default value because the default colour should come from the preset.
        $name = 'theme_bambuco/brandcolor' . $subthemekey;
        $title = get_string('brandcolor', 'theme_bambuco');
        $description = get_string('brandcolor_desc', 'theme_bambuco');
        $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    $lblhandwriting = get_string('fontfamily_handwriting', 'theme_bambuco');
    $lblicons = get_string('fontfamily_icons', 'theme_bambuco');

    // Google fonts available.
    $fonts = [
        'Agbalumo' => 'Agbalumo',
        'Caveat' => 'Caveat' . $lblhandwriting,
        'Dancing Script' => 'Dancing Script' . $lblhandwriting,
        'Dosis' => 'Dosis',
        'Droid Sans' => 'Droid Sans',
        'Edu TAS Begginer' => 'Edu TAS Begginer' . $lblhandwriting,
        'Exo 2' => 'Exo 2',
        'Great Vibes' => 'Great Vibes' . $lblhandwriting,
        'Griffy' => 'Griffy',
        'Inconsolata' => 'Inconsolata',
        'Indie Flower' => 'Indie Flower' . $lblhandwriting,
        'Josefin Sans' => 'Josefin Sans',
        'Kenia' => 'Kenia',
        'Lato' => 'Lato',
        'Lobster' => 'Lobster',
        'Mitr' => 'Mitr',
        'Montserrat' => 'Montserrat',
        'Noto Sans Symbols 2' => 'Noto Sans Symbols 2' . $lblicons,
        'Nunito' => 'Nunito',
        'Onest' => 'Onest',
        'Open Sans' => 'Open Sans',
        'Oswald' => 'Oswald',
        'Pacifico' => 'Pacifico' . $lblhandwriting,
        'Playfair Display' => 'Playfair Display',
        'Playpen Sans' => 'Playpen Sans' . $lblhandwriting,
        'Poppins' => 'Poppins',
        'Raleway' => 'Raleway',
        'Roboto' => 'Roboto',
        'Roboto Condensed' => 'Roboto Condensed',
        'Roboto Mono' => 'Roboto Mono',
        'Rubik' => 'Rubik',
        'Ruge Boogie' => 'Ruge Boogie' . $lblhandwriting,
        'Sacramento' => 'Sacramento' . $lblhandwriting,
        'Sora' => 'Sora',
        'Source Sans Pro' => 'Source Sans Pro',
        'Space Grotesk' => 'Space Grotesk',
        'Splash' => 'Splash' . $lblhandwriting,
        'Tangerine' => 'Tangerine' . $lblhandwriting,
        'Ubuntu' => 'Ubuntu',
        'Zeyada' => 'Zeyada' . $lblhandwriting,
    ];

    if (utils::iscustomizable_subtheme('fontfamily', $subtheme)) {
        // Font family to apply to the site.
        $name = 'theme_bambuco/fontfamily' . $subthemekey;
        $title = get_string('fontfamily', 'theme_bambuco');
        $description = get_string('fontfamily_desc', 'theme_bambuco');
        $setting = new admin_setting_configselect($name, $title, $description, '', ['' => ''] + $fonts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    if (utils::iscustomizable_subtheme('otherfontfamily', $subtheme)) {
        // Other fonts to include.
        $name = 'theme_bambuco/otherfontfamily' . $subthemekey;
        $title = get_string('otherfontfamily', 'theme_bambuco');
        $description = get_string('otherfontfamily_desc', 'theme_bambuco');
        $setting = new admin_setting_configmultiselect($name, $title, $description, [], $fonts);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }
}

$settings->add('theme_bambuco', $page);
