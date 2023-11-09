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
 * Language file.
 *
 * @package   theme_bambuco
 * @copyright 2023 David Herney Bernal - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['advancedsettings'] = 'Advanced settings';
$string['backgroundimage'] = 'Background image';
$string['backgroundimage_desc'] = 'The image to display as a background of the site. The background image you upload here will override the background image in your theme preset files.';
$string['contentbycategory'] = 'Content by category';
$string['contentbycategory_desc'] = 'Display content or a image in the course page based on the course category.
Use the structure (one by line): categoryid|imageurlorcontent';
$string['brandcolor'] = 'Brand colour';
$string['brandcolor_desc'] = 'The accent colour.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'A bootswatch is a set of Bootstrap variables and css to style Bootstrap';
$string['choosereadme'] = 'BambuCo is a modern highly-customisable theme. This theme is based in the Moodle theme: Boost.';
$string['configtitle'] = 'BambuCo';
$string['coursessettings'] = 'Courses settings';
$string['coursesheader'] = 'Courses header';
$string['coursesheader_desc'] = 'The header to display in the courses page.';
$string['coursesheader_default'] = 'Default';
$string['coursesheader_none'] = 'None';
$string['coursesheader_teacher'] = 'Teacher';
$string['courseheaderimage'] = 'Use the course header image';
$string['courseheaderimage_desc'] = 'Use a image in the course page header.';
$string['courseheaderimage_default'] = 'Configured image';
$string['courseheaderimage_none'] = 'None';
$string['courseheaderimage_overview'] = 'Course overview (configured if not exist)';
$string['courseheaderimage_overviewonly'] = 'Course overview (only if exist)';
$string['courseheaderimagefile'] = 'Header image file';
$string['courseheaderimagefile_desc'] = 'The image to display by default in the course header.';
$string['courseheaderimagetype'] = 'Header image type';
$string['courseheaderimagetype_desc'] = 'The type of image to display in the courses banner when the course don\t have a overview image.';
$string['courseheaderimagetype_default'] = 'Theme image';
$string['courseheaderimagetype_generated'] = 'Random generated texture';
$string['courseheaderlayout'] = 'Header layout';
$string['courseheaderlayout_desc'] = 'The layout of the course header.';
$string['courseheaderlayout_default'] = 'Default';
$string['courseheaderlayout_fullwidth'] = 'Full width';
$string['courseheaderview'] = 'Header view';
$string['courseheaderview_block'] = 'Content block pages';
$string['courseheaderview_course'] = 'Course index';
$string['courseheaderview_desc'] = 'Context to voew header.';
$string['courseheaderview_mod'] = 'Activity';
$string['courseheaderview_my'] = 'My courses';
$string['courseheaderview_report'] = 'Report';
$string['courseheaderview_user'] = 'User profile';
$string['coursemenu'] = 'Course menu';
$string['coursemenu_desc'] = 'Use the structure: capability|type|link|target|label|cssclass.<br />
- capability can be * to all users.<br />
- types available: url or mod_modulename (mod_forum, mod_assign, mod_quiz and others)<br />
- link can use {courseid} as a key. If type is mod_* can be "firstchild" for display only the first child. If type is url can be a relative or absolute url.<br />
- link target: _blank, _self or other anchor target option, can be empty to<br />
- firstchild: true or false';
$string['fontfamily'] = 'Font family';
$string['fontfamily_desc'] = 'The Google font family to use for the site.
View more in <a href="https://fonts.google.com/" target="_blank">Google Fonts</a>.
For symbols visit: <a href="https://fonts.google.com/noto/specimen/Noto+Sans+Symbols+2/glyphs?query=Noto+Sans+Symbols+2" target="_blank">Noto Sans Symbols 2 - Glyphs</a>.';
$string['fontfamily_icons'] = ' (icons)';
$string['fontfamily_handwriting'] = ' (handwriting)';
$string['generalsettings'] = 'General settings';
$string['loginbackgroundimage'] = 'Login page background image';
$string['loginbackgroundimage_desc'] = 'The image to display as a background for the login page.';
$string['loginformlayout'] = 'Login form layout';
$string['loginformlayout_desc'] = 'The layout of the login form. The default layout is the standard Moodle manual login. The external layout is intended to be used when the login is provided by an external system.';
$string['loginformlayout_default'] = 'Default';
$string['loginformlayout_toexternal'] = 'To external';
$string['loginsettings'] = 'Login settings';
$string['loginmanualtitle'] = 'With username and password';
$string['loginmorecontent'] = 'More content';
$string['loginmorecontent_desc'] = 'Additional content to display on the login page.';
$string['nobootswatch'] = 'None';
$string['otherfontfamily'] = 'Other font family';
$string['otherfontfamily_desc'] = 'Other fonts to include in the site. The font is not applied to the site, it is only included in the page.';
$string['pluginname'] = 'BambuCo';
$string['presetfiles'] = 'Additional theme preset files';
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme.
See <a href="https://docs.moodle.org/dev/Boost_Presets" target="_blanck">Boost presets</a> for information on creating and sharing your own preset files, and see the <a href="https://archive.moodle.net/boost" target="_blanck">Presets repository</a> for presets that others have shared.';
$string['preset'] = 'Theme preset';
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
$string['privacy:metadata'] = 'The BambuCo theme does not store any personal data about any user.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['region-above'] = 'Above';
$string['region-below'] = 'Below content';
$string['region-bottom'] = 'Bottom';
$string['region-intocontent'] = 'Content';
$string['region-top'] = 'Top';
$string['region-side-pre'] = 'Right';
$string['signup'] = 'Sign up';
$string['signuplink'] = 'Show signup link';
$string['signuplink_desc'] = 'Show a link to the signup page in the usermenu bar.';
$string['skin'] = 'Skin';
$string['skin_desc'] = 'Pick a skin to change the look of the theme.
The current skins are based on <a href="https://bootswatch.com/" target="_blanck">Bootswatch</a> project.
Check the <a href="https://bootswatch.com/" target="_blanck">Bootswatch page</a> for examples and more information.';
$string['skins_none'] = 'No skins are available.';
$string['skinsettings'] = 'Skin settings';
$string['unaddableblocks'] = 'Unneeded blocks';
$string['unaddableblocks_desc'] = 'The blocks specified are not needed when using this theme and will not be listed in the \'Add a block\' menu.';
$string['privacy:metadata:preference:draweropenblock'] = 'The user\'s preference for hiding or showing the drawer with blocks.';
$string['privacy:metadata:preference:draweropenindex'] = 'The user\'s preference for hiding or showing the drawer with course index.';
$string['privacy:metadata:preference:draweropennav'] = 'The user\'s preference for hiding or showing the drawer menu navigation.';
$string['privacy:drawerindexclosed'] = 'The current preference for the index drawer is closed.';
$string['privacy:drawerindexopen'] = 'The current preference for the index drawer is open.';
$string['privacy:drawerblockclosed'] = 'The current preference for the block drawer is closed.';
$string['privacy:drawerblockopen'] = 'The current preference for the block drawer is open.';
