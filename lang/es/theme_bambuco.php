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
 * @copyright 2023 David Herney - cirano. https://bambuco.co
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['advancedsettings'] = 'Configuración avanzada';
$string['altcha_arialinklabel'] = 'Visita Altcha.org';
$string['altcha_error'] = 'Error de verificación. Inténtalo de nuevo.';
$string['altcha_expired'] = 'Verificación expirada. Inténtalo de nuevo.';
$string['altcha_footer'] = 'Protegido por <a href="https://altcha.org/" target="_blank" aria-label="Visita Altcha.org">ALTCHA</a>';
$string['altcha_label'] = 'No soy un robot';
$string['altcha_verified'] = 'Verificado';
$string['altcha_verifying'] = 'Verificando...';
$string['altcha_waitalert'] = 'Verificando... por favor espera.';
$string['altchalevel'] = 'ALTCHA - Nivel';
$string['altchalevel_1'] = 'Bajo';
$string['altchalevel_10'] = 'Muy alto';
$string['altchalevel_2'] = 'Básico';
$string['altchalevel_5'] = 'Alto';
$string['altchalevel_desc'] = 'El nivel de ALTCHA a utilizar. Si el nivel aumenta, la validación tardará más, pero será más segura.';
$string['altchavalidtime'] = 'ALTCHA - Tiempo de validez';
$string['altchavalidtime_10s'] = '10 segundos';
$string['altchavalidtime_1m'] = '1 minuto';
$string['altchavalidtime_20s'] = '20 segundos';
$string['altchavalidtime_2m'] = '2 minutos';
$string['altchavalidtime_40s'] = '40 segundos';
$string['altchavalidtime_5m'] = '5 minutos';
$string['altchavalidtime_desc'] = 'El tiempo de validez de la verificación ALTCHA. Si se excede, el usuario debe volver a verificar.';
$string['backgroundimage'] = 'Imagen de fondo';
$string['backgroundimage_desc'] = 'La imagen que se mostrará como fondo del sitio. La imagen de fondo que cargue aquí anulará la imagen de fondo en los archivos preestablecidos de su tema.';
$string['bbcoscss'] = 'SCSS sin formato';
$string['bbcoscss_desc'] = 'Utilice este campo para proporcionar código SCSS o CSS que se inyectará al final de la hoja de estilo.';
$string['bbcoscsspre'] = 'SCSS inicial sin formato';
$string['bbcoscsspre_desc'] = 'En este campo puede proporcionar el código SCSS de inicialización, se inyectará antes que todo lo demás. La mayoría de las veces se usa esta configuración para definir variables.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'Bootswatch es un conjunto de variables y CSS que personaliza Bootstrap';
$string['brandcolor'] = 'Color de marca';
$string['brandcolor_desc'] = 'El color que se acentua en el sitio.';
$string['cachedef_postprocessedcss'] = 'Cachedef css posprocesado';
$string['choosereadme'] = 'BambuCo es un tema moderno altamente personalizable. Este tema está basado en el tema de Moodle: Boost.';
$string['configtitle'] = 'BambuCo';
$string['contentbycategory'] = 'Contenido por categoría';
$string['contentbycategory_desc'] = 'Mostrar contenido o una imagen en la página del curso basado en la categoría del curso.
Use la estructura (una por línea): iddecategoría|contenidoourldeimagen';
$string['courseheaderimage'] = 'Usar la imagen del encabezado del curso';
$string['courseheaderimage_default'] = 'Imagen configurada';
$string['courseheaderimage_desc'] = 'Usar una imagen en el encabezado de la página del curso';
$string['courseheaderimage_none'] = 'Ninguna';
$string['courseheaderimage_overview'] = 'Imagen del curso (la configurada si no existe una del curso)';
$string['courseheaderimage_overviewonly'] = 'Imagen del curso (sólo si existe)';
$string['courseheaderimagefile'] = 'Archivo de imagen del encabezado';
$string['courseheaderimagefile_desc'] = 'La imagen que se mostrará de forma predeterminada en el encabezado del curso.';
$string['courseheaderimagetype'] = 'Tipo de imagen del encabezado';
$string['courseheaderimagetype_default'] = 'Imagen del tema';
$string['courseheaderimagetype_desc'] = 'El tipo de imagen que se mostrará en el banner de los cursos cuando el curso no tenga una imagen de descripción general.';
$string['courseheaderimagetype_generated'] = 'Textura generada aleatoriamente';
$string['courseheaderlayout'] = 'Diseño del encabezado';
$string['courseheaderlayout_default'] = 'Predeterminado';
$string['courseheaderlayout_desc'] = 'El diseño del encabezado del curso.';
$string['courseheaderlayout_fullwidth'] = 'Ancho completo';
$string['courseheaderview'] = 'Vista del encabezado';
$string['courseheaderview_anycourse'] = 'Cualquier página del curso';
$string['courseheaderview_block'] = 'Páginas de contenido de los bloque';
$string['courseheaderview_course'] = 'Inicio del curso';
$string['courseheaderview_desc'] = 'Contexto para personalizar el encabezado.';
$string['courseheaderview_grade'] = 'Libro de calificaciones';
$string['courseheaderview_mod'] = 'Actividad';
$string['courseheaderview_my'] = 'Mis cursos';
$string['courseheaderview_report'] = 'Reportes';
$string['courseheaderview_user'] = 'Perfil de usuario';
$string['coursemenu'] = 'Menú del curso';
$string['coursemenu_desc'] = 'Utilice la estructura: capacidad|tipo|enlace|destino|etiqueta|claseCSS.<br />
- La capacidad puede ser * para todos los usuarios.<br />
- Tipos disponibles: url o mod_modulename (mod_forum, mod_assign, mod_quiz y otros)<br />
- El enlace puede utilizar {courseid} como clave. Si el tipo es mod_* puede ser "firstchild" para mostrar solo el primer hijo. Si el tipo es URL, puede ser una URL relativa o absoluta.<br />
- Destino del enlace: _blank, _self u otra opción de destino ancla, también puede estar vacío.';
$string['coursesheader'] = 'Encabezado de cursos';
$string['coursesheader_basic'] = 'Básico';
$string['coursesheader_default'] = 'Predeterminado';
$string['coursesheader_desc'] = 'El encabezado para mostrar en la página de cursos.';
$string['coursesheader_none'] = 'Ninguno';
$string['coursesheader_teacher'] = 'Profesor';
$string['coursesheaderposition'] = 'Posición del encabezado de cursos';
$string['coursesheaderposition_content'] = 'En el contenido';
$string['coursesheaderposition_desc'] = 'La posición del encabezado de cursos.';
$string['coursesheaderposition_over'] = 'Sobre la página';
$string['coursesheaderposition_top'] = 'En la parte superior';
$string['coursessettings'] = 'Configuración de cursos';
$string['coursewidthfield'] = 'Campo de ancho del curso';
$string['coursewidthfield_desc'] = 'El campo a utilizar como ancho del curso en la página del curso. En el valor de dicho campo se puede utilizar un valor en porcentaje, en una medida fija como px o em, o utilizar la palabra clave <b>unlimitedwidth</b>.';
$string['customizesubtheme'] = 'Personalizar';
$string['defaultsubtheme'] = 'Volver al tema predeterminado';
$string['editingsubtheme'] = 'Editando subtema <b>{$a}</b>.';
$string['eventsubtheme_created'] = 'Subtema creado';
$string['eventsubtheme_deleted'] = 'Subtema eliminado';
$string['eventsubtheme_updated'] = 'Subtema actualizado';
$string['fontfamily'] = 'Familia de la fuente';
$string['fontfamily_desc'] = 'Fuente de Google que se utilizará en el sitio.
Ver más en <a href="https://fonts.google.com/" target="_blank">Google Fonts</a>.
Para símbolos, visite: <a href="https://fonts.google.com/noto/specimen/Noto+Sans+Symbols+2/glyphs?query=Noto+Sans+Symbols+2" target="_blank">Noto Sans Symbols 2 - Glyphs</a>.';
$string['fontfamily_handwriting'] = ' (escritura a mano)';
$string['fontfamily_icons'] = '(iconos)';
$string['generalsettings'] = 'Configuración general';
$string['loginbackgroundimage'] = 'Imagen de fondo';
$string['loginbackgroundimage_desc'] = 'La imagen que se mostrará como fondo para la página de inicio de sesión.';
$string['loginformlayout'] = 'Diseño del formulario';
$string['loginformlayout_default'] = 'Predeterminado';
$string['loginformlayout_desc'] = 'El diseño del formulario de inicio de sesión. El diseño predeterminado es el inicio de sesión manual estándar de Moodle. El diseño externo está pensado para usarse cuando el inicio de sesión lo proporciona un sistema externo.';
$string['loginformlayout_toexternal'] = 'A externo';
$string['loginmanualtitle'] = 'Usar nombre de usuario y contraseña';
$string['loginmorecontent'] = 'Más contenido';
$string['loginmorecontent_desc'] = 'Contenido adicional para mostrar en la página de inicio de sesión.';
$string['loginsettings'] = 'Configuración de inicio de sesión';
$string['multitheme'] = 'Multitema';
$string['multithemecoursefield'] = 'Campo personalizado del curso';
$string['multithemecoursefield_desc'] = 'Campo personalizado del curso para asignar un subtema.
El valor del campo debe coincidir con el ID del subtema; de lo contrario, se mostrará el tema predeterminado.';
$string['multithemeenabled'] = 'Habilitar multitema';
$string['multithemeenabled_desc'] = 'Habilite la función multitema. Esta función le permite asignar un subtema a un usuario o a un curso según un campo de perfil de usuario o un campo personalizado de curso.';
$string['multithemeuserfield'] = 'Campo de perfil de usuario';
$string['multithemeuserfield_desc'] = 'Campo de perfil de usuario para asignar un subtema.
El valor del campo debe coincidir con el ID del subtema; de lo contrario, se mostrará el tema predeterminado.';
$string['nobootswatch'] = 'Ninguno';
$string['otherfontfamily'] = 'Otra fuentes';
$string['otherfontfamily_desc'] = 'Otras fuentes a incluir en el sitio. La fuente no se aplica al sitio, sólo se incluye en la página.';
$string['pluginname'] = 'BambuCo';
$string['potentialidpsregister'] = 'Regístrese usando su cuenta de:';
$string['potentialidpsregister_help'] = 'Puede registrarse en el sitio usando una cuenta de un sitio externo. Su cuenta se creará con los datos que proporcione esa plataforma y podrá seguir entrando con la misma cuenta.';
$string['preset'] = 'Tema preestablecido';
$string['preset_desc'] = 'Elija un ajuste preestablecido para cambiar ampliamente el aspecto del tema.';
$string['presetfiles'] = 'Archivos preestablecidos';
$string['presetfiles_desc'] = 'Los archivos preestablecidos se pueden utilizar para alterar drásticamente la apariencia del tema.
Consulte <a href="https://docs.moodle.org/dev/Boost_Presets" target="_blanck">Boost presets</a> para obtener información sobre cómo crear y compartir sus propios archivos preestablecidos, y consulte el <a href= "https://archive.moodle.net/boost" target="_blanck">Repositorio de ajustes preestablecidos</a> para ajustes preestablecidos que otros han compartido.';
$string['privacy:drawerblockclosed'] = 'La preferencia actual para la sección de bloques está cerrada.';
$string['privacy:drawerblockopen'] = 'La preferencia actual para la sección de bloques está abierta.';
$string['privacy:drawerindexclosed'] = 'La preferencia actual para la sección de índice está cerrada.';
$string['privacy:drawerindexopen'] = 'La preferencia actual para la sección de índice está abierta.';
$string['privacy:metadata'] = 'El tema BambuCo no almacena ningún dato personal sobre ningún usuario.';
$string['privacy:metadata:preference:draweropenblock'] = 'La preferencia del usuario por ocultar o mostrar la sección de bloques.';
$string['privacy:metadata:preference:draweropenindex'] = 'La preferencia del usuario por ocultar o mostrar la sección con el índice del curso.';
$string['privacy:metadata:preference:draweropennav'] = 'La preferencia del usuario para ocultar o mostrar la sección con la navegación de menú.';
$string['region-above'] = 'Superior';
$string['region-below'] = 'Bajo el contenido';
$string['region-bottom'] = 'Abajo';
$string['region-content'] = 'Como contenido';
$string['region-intocontent'] = 'En el contenido';
$string['region-side-pre'] = 'Derecha';
$string['region-top'] = 'Arriba';
$string['returntohome'] = 'Regresar al inicio';
$string['settingsfulldescription'] = 'Este tema está organizado para la edición en páginas y así evitar la sobrecarga que puede resultar al agrupar todas las configuraciones en una sola página. <br>
Puedes editar cada configuración en:<br>
<ul>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_general">General</a></li>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_advanced">Avanzado</a></li>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_skin">Máscara</a>: Cambiar plantilla de Bootstrap</li>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_login">Inicio de sesión</a>: Estilos para la página de inicio de sesión y registro.</li>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_courses">Cursos</a></li>
    <li><a href="{$a}admin/settings.php?section=theme_bambuco_multitheme">Multitema</a></li>
    <li><a href="{$a}theme/bambuco/subthemes.php">Subtemas</a>: Los subtemas permiten crear estilos para cursos y usuarios específicos. Esta opción debe habilitarse en la sección Multitema.</li>
</ul>
<p><b>Este tema fue creado y es mantenido libre por <a href="https://bambuco.co" target="_blank">BambuCo</a></b>.</p>';
$string['signup'] = 'Registrarse';
$string['signupidentityproviders'] = 'Mostrar registro con externos';
$string['signupidentityproviders_desc'] = 'Mostrar enlace para usar servicios externos en la página de registro.';
$string['signuplink'] = 'Enlace de registro';
$string['signuplink_desc'] = 'Mostrar un enlace a la página de registro en la barra del menú de usuario.';
$string['skin'] = 'Máscara';
$string['skin_desc'] = 'Elija una máscara para cambiar el aspecto del tema.
Las opciones actuales se basan en el proyecto <a href="https://bootswatch.com/" target="_blanck">Bootswatch</a>.
Consulta la <a href="https://bootswatch.com/" target="_blanck">página de Bootswatch</a> para ver ejemplos y más información.';
$string['skins_none'] = 'No hay máscaras disponibles.';
$string['skinsettings'] = 'Máscaras';
$string['subtheme_homeurl'] = 'URL de inicio';
$string['subtheme_homeurl_help'] = 'URL de inicio para redirigir al usuario cuando se usa el subtema. La URL puede ser relativa o absoluta.';
$string['subtheme_idnumber'] = 'ID del subtema';
$string['subtheme_idnumber_help'] = 'Este ID se utiliza para asociar el subtema con el perfil de usuario o el campo personalizado del curso.';
$string['subtheme_inherit'] = 'Heredar';
$string['subtheme_inherit_help'] = 'El subtema heredará la configuración del tema principal.';
$string['subtheme_join'] = 'Unirse';
$string['subtheme_join_help'] = 'El subtema se unirá a la configuración del tema principal.';
$string['subtheme_name'] = 'Nombre';
$string['subtheme_name_help'] = 'El nombre visible del subtema.';
$string['subtheme_overwrite'] = 'Sobrescribir';
$string['subtheme_overwrite_help'] = 'El subtema sobrescribirá la configuración del tema principal.';
$string['subthemedelete'] = 'Eliminar subtema';
$string['subthemedeleted'] = 'Subtema eliminado';
$string['subthemes'] = 'Subtemas';
$string['unaddableblocks'] = 'Bloques innecesarios';
$string['unaddableblocks_desc'] = 'Los bloques especificados no son necesarios cuando se utiliza este tema y no aparecerán en el menú \'Agregar un bloque\'.';
$string['usealtcha'] = 'Usar ALTCHA';
$string['usealtcha_desc'] = 'Usar ALTCHA para verificar que el usuario no es un robot.';
