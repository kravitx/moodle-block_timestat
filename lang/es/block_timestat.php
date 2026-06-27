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
 * Cadenas de idioma en espanol para el bloque Timestat.
 *
 * @package    block_timestat
 * @copyright  2014 Barbara Debska, Lukasz Sanokowski, Lukasz Musial
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockname'] = 'Timestat';
$string['pluginname'] = 'Timestat';
$string['blocktitle'] = 'Tiempo conectado al curso';
$string['nologs'] = 'No se encontraron registros.';
$string['calculate'] = 'Calcular';
$string['viewreport'] = 'Ver informe';
$string['summary'] = 'Tiempo total del curso';
$string['start'] = 'Inicio:';
$string['end'] = 'Fin:';
$string['days'] = ' dias ';
$string['hours'] = ' horas ';
$string['minuts'] = ' minutos ';
$string['seconds'] = ' segundos ';
$string['time'] = 'Tiempo';
$string['timespent'] = 'Tiempo invertido';
$string['choosetimeperiod'] = 'Elegir periodo de tiempo';
$string['loginterval'] = 'Intervalo de registro (segundos)';
$string['loginterval_desc'] = 'Intervalo de tiempo en el que se registra la actividad del usuario. El valor minimo es 10 segundos.';
$string['inactivitytime'] = 'Tiempo de inactividad (pantallas grandes) (segundos)';
$string['inactivitytime_desc'] = 'Tiempo en segundos tras el cual el usuario se considera inactivo. El valor minimo es 10 segundos.';
$string['inactivitytime_small'] = 'Tiempo de inactividad (pantallas pequenas)';
$string['inactivitytime_small_desc'] = 'Tiempo en segundos tras el cual el usuario se considera inactivo cuando la actividad se registra en pantallas pequenas. El valor minimo es 10 segundos.';
$string['ignoreinactivity'] = 'Ignorar inactividad';
$string['ignoreinactivity_desc'] = 'Si se activa, el tiempo se sigue contabilizando mientras la pagina permanezca abierta aunque el usuario no interactue con ella.';
$string['loginterval_help'] = 'Intervalo de tiempo en el que se registra la actividad del usuario.';
$string['showtimer'] = 'Mostrar temporizador';
$string['showtimer_desc'] = 'Si se activa, el contador de tiempo sera visible para todos los usuarios matriculados. Si se desactiva, solo sera visible para quienes tengan la capacidad "block/timestat:viewtimer".';
$string['reportedtime'] = 'Tiempo reportado';
$string['loading'] = 'Cargando...';
$string['notrackinglog'] = 'No se ha encontrado un registro de seguimiento valido para esta solicitud.';
$string['timestat:viewreport'] = 'Ver informe';
$string['timestat:viewtimer'] = 'Ver temporizador';
$string['timestat:addinstance'] = 'Agregar un nuevo bloque Timestat';
$string['timestat:view'] = 'Ver el bloque Timestat';
$string['privacy:metadata:block_timestat'] = 'Bloque Timestat';
$string['privacy:metadata:block_timestat:log_id'] = 'El ID del registro relacionado con la entrada de log del usuario.';
$string['privacy:metadata:block_timestat:timespent'] = 'Tiempo invertido por el usuario en la entrada de log.';
$string['privacy:metadata:block_timestat_session'] = 'Estado del navegador utilizado para evitar registros de tiempo duplicados.';
$string['privacy:metadata:block_timestat_session:userid'] = 'El usuario cuyo tiempo se registra.';
$string['privacy:metadata:block_timestat_session:courseid'] = 'El curso cuyo tiempo se registra.';
$string['privacy:metadata:block_timestat_session:clientid'] = 'Un identificador aleatorio de la sesion del navegador.';
$string['privacy:metadata:block_timestat_session:reportedseconds'] = 'Los segundos acumulados recibidos de esta sesion del navegador.';
$string['privacy:metadata:block_timestat_session:lastsequence'] = 'La ultima solicitud ordenada recibida de esta sesion del navegador.';
$string['privacy:metadata:block_timestat_session:active'] = 'Indica si esta sesion del navegador esta contando tiempo actualmente.';
$string['privacy:metadata:block_timestat_session:timemodified'] = 'La ultima vez que esta sesion notifico actividad.';
$string['privacy:metadata:block_timestat_account'] = 'Estado compartido utilizado para combinar navegadores simultaneos.';
$string['privacy:metadata:block_timestat_account:userid'] = 'El usuario cuyo tiempo se registra.';
$string['privacy:metadata:block_timestat_account:courseid'] = 'El curso cuyo tiempo se registra.';
$string['privacy:metadata:block_timestat_account:accounteduntil'] = 'El final del ultimo intervalo ya contabilizado.';
$string['cannotacquirelock'] = 'El seguimiento esta ocupado. La solicitud se puede reintentar de forma segura.';
$string['selectauser'] = 'Seleccionar un usuario';
$string['sortby'] = 'Ordenar por';
$string['sort_timespent_desc'] = 'Tiempo invertido (mayor a menor)';
$string['sort_lastname_asc'] = 'Apellidos';
$string['sort_firstname_asc'] = 'Nombre';
$string['trackeditingteachers'] = 'Rastrear profesores con permiso de edición';
$string['trackeditingteachers_desc'] = 'Permite registrar el tiempo de los usuarios con el rol de profesor con permiso de edición.';
$string['trackteachers'] = 'Rastrear profesores sin permiso de edición';
$string['trackteachers_desc'] = 'Permite registrar el tiempo de los usuarios con el rol de profesor sin permiso de edición.';
$string['err_min10'] = 'El valor debe ser un número mayor o igual a 10.';
