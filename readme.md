# Timestat block for Moodle

## English

Timestat is a Moodle block that measures active time spent by enrolled users inside a course.

### Requirements

- Moodle 3.11 or later
- Plugin release: 2.0.17

### Installation

Install the block in the standard Moodle way:

1. Copy the plugin to `blocks/timestat`.
2. Visit `Site administration > Notifications`.

You can also install it from the Moodle administration UI as a ZIP package.

### Features

- Tracks active time for enrolled users inside course contexts.
- Ignores guest users and site administrators.
- Pauses tracking after a configurable inactivity period based on browser activity.
- Can optionally ignore inactivity and keep counting while the page remains open.
- Offers an optional visual timer showing the accumulated course total for tracked users.
- Offers an aggregated report page with filters for user, group, date range, activity and sort order.
- Supports on-page viewing plus CSV and Excel exports from the report page.
- Keeps one authoritative course total per user even when several tabs or browser windows are open.
- Prevents duplicate counting caused by overlapping requests, page changes or retries.

### How tracking works

Tracking is available only in course contexts, not on the site home page. The user must be logged in, enrolled in the course, and allowed by the plugin rules to be tracked.

By default, students and other enrolled non-admin users are tracked. Site administrators are never tracked. Editing teachers and non-editing teachers are tracked only when the corresponding plugin settings are enabled.

Tracking does not depend only on the block being visible on the page. The plugin injects its tracking bootstrap through Moodle output hooks, and the block output also includes a fallback bootstrap for pages where the global hook is not available.

The browser reports cumulative tracked time through an AJAX web service. The server deduplicates reports by combining a per-browser session state with a shared per-user, per-course accounting frontier.

Time can be tracked even when the visual counter is not shown to the learner. The visible timer is synchronized with the server-side course total so that inactive tabs stay up to date while another tab remains active.

### Block placement and visibility

The block declares support for Moodle page formats `course-view`, `course-view-social`, `mod`, `mod-quiz`, and `course`. Actual placement still depends on whether the current page layout exposes block regions.

Only one block instance is allowed. If you want the same block UI to appear across multiple course pages and activities, use Moodle's own block placement tools. One possible Moodle-level option is to make the block sticky throughout the course:

https://docs.moodle.org/400/en/Block_settings#Making_a_block_sticky_throughout_a_course

On quiz attempt pages, block visibility depends on the Moodle page layout and configuration rather than on plugin-specific logic.

The timer UI and the report link belong to the block instance. Tracking can still run even when that UI is not shown on the page.

### Permissions

Main capabilities:

- `block/timestat:view`: allows the block and tracking logic to be used in the course.
- `block/timestat:viewreport`: allows access to the report page.
- `block/timestat:viewtimer`: allows tracked users to view the visual timer when it is not globally enabled.
- `block/timestat:addinstance`: allows adding the block to a page.

By default, students can access the block, while report access is limited to teaching and management roles.

### Configuration

Plugin settings are available at `Site administration > Plugins > Blocks > Timestat`.

Current settings:

- `Show timer`
- `Log interval`
- `Inactivity time (big screens)`
- `Inactivity time (small screens)`
- `Ignore inactivity`
- `Track editing teachers`
- `Track teachers`

The minimum allowed value for `Log interval` and both inactivity settings is 10 seconds.

### Stored data and privacy

The plugin stores:

- `block_timestat`: time spent records linked to tracked log entries
- `block_timestat_session`: browser session state used to avoid duplicate counting
- `block_timestat_account`: shared per-user, per-course tracking state used to merge simultaneous browser sessions

Privacy metadata is implemented in `classes/privacy/provider.php`.

### Recent changes

Recent 2.0.x updates improved tracking reliability:

- idempotent reporting across page changes and request retries
- one shared course timer per user across multiple browsers or tabs
- synchronization of the visible timer with the authoritative server total

See `changelog.txt` for the full history.

### Credits

The version of the plugin for Moodle 2.9 and earlier was developed by:

- Barbara Debska
- Lukasz Musial
- Lukasz Sanokowski

Upgrade from 1.9 to 2.5 was made thanks to contributions from:

- Classroom Revolution
- Lib Ertea
- Mart van der Niet
- Joseph Thibault

## Español

Timestat es un bloque para Moodle que mide el tiempo de actividad real de los usuarios matriculados dentro de un curso.

### Requisitos

- Moodle 3.11 o superior
- Versión del plugin: 2.0.17

### Instalación

Instala el bloque de la forma habitual en Moodle:

1. Copia el plugin en `blocks/timestat`.
2. Visita `Administración del sitio > Notificaciones`.

También puedes instalarlo desde la interfaz de administración de Moodle como paquete ZIP.

### Funcionalidades

- Registra el tiempo de actividad de usuarios matriculados dentro de contextos de curso.
- Ignora a los usuarios invitados y a los administradores del sitio.
- Pausa el seguimiento tras un periodo configurable de inactividad según la actividad del navegador.
- Puede ignorar la inactividad y seguir contando mientras la página permanezca abierta.
- Ofrece un temporizador visual opcional con el total acumulado del curso para los usuarios que realmente se están registrando.
- Ofrece una página de informe agregado con filtros por usuario, grupo, rango de fechas, actividad y criterio de ordenación.
- Permite visualizar el informe en pantalla y exportarlo en CSV y Excel.
- Mantiene un único total autoritativo por usuario y curso aunque haya varias pestañas o ventanas abiertas.
- Evita el doble conteo provocado por solicitudes solapadas, cambios de página o reintentos.

### Cómo funciona el seguimiento

El seguimiento solo está disponible en contextos de curso, no en la página principal del sitio. El usuario debe haber iniciado sesión, estar matriculado en el curso y cumplir las reglas del plugin para ser registrado.

Por defecto, se registra a estudiantes y a otros usuarios matriculados que no sean administradores. Los administradores del sitio no se registran nunca. Los profesores editores y no editores solo se registran si se activan sus opciones correspondientes en la configuración del plugin.

El seguimiento no depende únicamente de que el bloque sea visible en la página. El plugin inyecta el arranque del tracker mediante hooks de salida de Moodle, y la salida del bloque también incluye un mecanismo de respaldo para páginas donde el hook global no está disponible.

El navegador envía el tiempo acumulado mediante un servicio AJAX. El servidor elimina duplicados combinando un estado por sesión de navegador con un estado compartido de cómputo por usuario y curso.

El tiempo puede seguir registrándose aunque el contador visual no se muestre al usuario. El temporizador visible se sincroniza con el total autoritativo del servidor para que las pestañas inactivas se mantengan actualizadas mientras otra pestaña sigue activa.

### Ubicación y visibilidad del bloque

El bloque declara soporte para los formatos de página de Moodle `course-view`, `course-view-social`, `mod`, `mod-quiz` y `course`. Su colocación real sigue dependiendo de que el diseño de la página exponga regiones de bloques.

Solo se permite una instancia del bloque. Si quieres que la misma interfaz del bloque aparezca en varias páginas del curso y en sus actividades, debes usar las herramientas de colocación de bloques propias de Moodle. Una posibilidad a nivel de Moodle es convertirlo en un bloque persistente dentro del curso:

https://docs.moodle.org/400/en/Block_settings#Making_a_block_sticky_throughout_a_course

En los intentos de cuestionario, la visibilidad del bloque depende del diseño y de la configuración de Moodle para esa página, no de una lógica específica del plugin.

La interfaz del temporizador y el enlace al informe pertenecen a la instancia del bloque. El seguimiento puede seguir funcionando aunque esa interfaz no se muestre en la página.

### Permisos

Capacidades principales:

- `block/timestat:view`: permite usar el bloque y la lógica de seguimiento dentro del curso.
- `block/timestat:viewreport`: permite acceder a la página del informe.
- `block/timestat:viewtimer`: permite que los usuarios que sí están siendo registrados vean el temporizador visual cuando no está habilitado globalmente.
- `block/timestat:addinstance`: permite añadir el bloque a una página.

Por defecto, los estudiantes pueden acceder al bloque, mientras que el acceso al informe queda limitado a roles docentes y de gestión.

### Configuración

La configuración del plugin está disponible en `Administración del sitio > Plugins > Bloques > Timestat`.

Opciones actuales:

- `Show timer`
- `Log interval`
- `Inactivity time (big screens)`
- `Inactivity time (small screens)`
- `Ignore inactivity`
- `Track editing teachers`
- `Track teachers`

El valor mínimo permitido para `Log interval` y para ambos ajustes de inactividad es de 10 segundos.

### Datos almacenados y privacidad

El plugin almacena:

- `block_timestat`: registros de tiempo asociados a las entradas de log monitorizadas
- `block_timestat_session`: estado de sesión del navegador para evitar conteos duplicados
- `block_timestat_account`: estado compartido por usuario y curso para combinar sesiones simultáneas de varios navegadores

La metadata de privacidad está implementada en `classes/privacy/provider.php`.

### Cambios recientes

Las últimas versiones 2.0.x han mejorado la fiabilidad del seguimiento:

- reporte idempotente ante cambios de página y reintentos
- un único temporizador compartido por usuario entre varios navegadores o pestañas
- sincronización del temporizador visible con el total autoritativo del servidor

Consulta `changelog.txt` para ver el historial completo.

### Créditos

La versión del plugin para Moodle 2.9 y anteriores fue desarrollada por:

- Barbara Debska
- Lukasz Musial
- Lukasz Sanokowski

La actualización de la versión 1.9 a la 2.5 fue posible gracias a las contribuciones de:

- Classroom Revolution
- Lib Ertea
- Mart van der Niet
- Joseph Thibault

## License

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
