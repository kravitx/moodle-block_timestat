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

- Tracks active time for enrolled users in real courses.
- Ignores guest users and site administrators.
- Pauses tracking after a configurable inactivity period based on browser activity.
- Can optionally ignore inactivity and keep counting while the page remains open.
- Offers an optional visual timer showing the accumulated course total.
- Offers a report page with filters for user, group, date range, activity and sort order.
- Supports CSV and Excel exports from the report page.
- Keeps one authoritative course total per user even when several tabs or browser windows are open.
- Prevents duplicate counting caused by overlapping requests, page changes or retries.

### How tracking works

Tracking is available only in course contexts, not on the site home page. The user must be logged in, enrolled in the course, and allowed by the plugin rules to be tracked.

By default, students and other enrolled non-admin users are tracked. Site administrators are never tracked. Editing teachers and non-editing teachers are tracked only when the corresponding plugin settings are enabled.

Tracking does not depend only on the block being visible on the page. The plugin injects its tracking bootstrap through Moodle hooks, and the block output also includes a fallback bootstrap for pages where the global hook is not available.

Time can be tracked even when the visual counter is not shown to the learner. The visible timer is synchronized with the server-side course total so that inactive tabs stay up to date while another tab remains active.

### Block placement and visibility

The block can be added to course and module pages. If you want the block UI itself to be visible on the course page and activity pages, make it sticky throughout the course:

https://docs.moodle.org/400/en/Block_settings#Making_a_block_sticky_throughout_a_course

For quiz attempt pages, enable `Show blocks during the attempt` in the quiz appearance settings.

The timer UI and the report link belong to the block UI. Tracking can still run when that UI is not shown.

### Permissions

Main capabilities:

- `block/timestat:view`: allows the block and tracking logic to be used in the course.
- `block/timestat:viewreport`: allows access to the detailed report.
- `block/timestat:viewtimer`: allows viewing the visual timer when it is not globally enabled.
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

### Stored data and privacy

The plugin stores:

- time spent records linked to tracked log entries
- browser session state used to avoid duplicate counting
- shared per-user, per-course tracking state used to merge simultaneous browser sessions

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

## Espanol

Timestat es un bloque para Moodle que mide el tiempo de actividad real de los usuarios matriculados dentro de un curso.

### Requisitos

- Moodle 3.11 o superior
- Version del plugin: 2.0.17

### Instalacion

Instala el bloque de la forma habitual en Moodle:

1. Copia el plugin en `blocks/timestat`.
2. Visita `Administracion del sitio > Notificaciones`.

Tambien puedes instalarlo desde la interfaz de administracion de Moodle como paquete ZIP.

### Funcionalidades

- Registra el tiempo de actividad de usuarios matriculados en cursos reales.
- Ignora a los usuarios invitados y a los administradores del sitio.
- Pausa el seguimiento tras un periodo configurable de inactividad segun la actividad del navegador.
- Puede ignorar la inactividad y seguir contando mientras la pagina permanezca abierta.
- Ofrece un temporizador visual opcional con el total acumulado del curso.
- Ofrece una pagina de informe con filtros por usuario, grupo, rango de fechas, actividad y criterio de ordenacion.
- Permite exportar el informe en CSV y Excel.
- Mantiene un unico total autoritativo por usuario y curso aunque haya varias pestanas o ventanas abiertas.
- Evita el doble conteo provocado por solicitudes solapadas, cambios de pagina o reintentos.

### Como funciona el seguimiento

El seguimiento solo esta disponible en contextos de curso, no en la pagina principal del sitio. El usuario debe haber iniciado sesion, estar matriculado en el curso y cumplir las reglas del plugin para ser registrado.

Por defecto, se registra a estudiantes y a otros usuarios matriculados que no sean administradores. Los administradores del sitio no se registran nunca. Los profesores editores y no editores solo se registran si se activan sus opciones correspondientes en la configuracion del plugin.

El seguimiento no depende unicamente de que el bloque sea visible en la pagina. El plugin inyecta el arranque del tracker mediante hooks de Moodle, y la salida del bloque tambien incluye un mecanismo de respaldo para paginas donde el hook global no esta disponible.

El tiempo puede seguir registrandose aunque el contador visual no se muestre al usuario. El temporizador visible se sincroniza con el total autoritativo del servidor para que las pestanas inactivas se mantengan actualizadas mientras otra pestana sigue activa.

### Ubicacion y visibilidad del bloque

El bloque puede anadirse a paginas de curso y de modulos. Si quieres que la interfaz del bloque se vea en la pagina principal del curso y en las actividades, puedes convertirlo en un bloque persistente dentro del curso:

https://docs.moodle.org/400/en/Block_settings#Making_a_block_sticky_throughout_a_course

En los intentos de cuestionario, activa `Show blocks during the attempt` en la configuracion de apariencia del cuestionario.

La interfaz del temporizador y el enlace al informe pertenecen a la UI del bloque. El seguimiento puede seguir funcionando aunque esa UI no se muestre.

### Permisos

Capacidades principales:

- `block/timestat:view`: permite usar el bloque y la logica de seguimiento dentro del curso.
- `block/timestat:viewreport`: permite acceder al informe detallado.
- `block/timestat:viewtimer`: permite ver el temporizador visual cuando no esta habilitado globalmente.
- `block/timestat:addinstance`: permite anadir el bloque a una pagina.

Por defecto, los estudiantes pueden acceder al bloque, mientras que el acceso al informe queda limitado a roles docentes y de gestion.

### Configuracion

La configuracion del plugin esta disponible en `Administracion del sitio > Plugins > Bloques > Timestat`.

Opciones actuales:

- `Show timer`
- `Log interval`
- `Inactivity time (big screens)`
- `Inactivity time (small screens)`
- `Ignore inactivity`
- `Track editing teachers`
- `Track teachers`

### Datos almacenados y privacidad

El plugin almacena:

- registros de tiempo asociados a las entradas de log monitorizadas
- estado de sesion del navegador para evitar conteos duplicados
- estado compartido por usuario y curso para combinar sesiones simultaneas de varios navegadores

La metadata de privacidad esta implementada en `classes/privacy/provider.php`.

### Cambios recientes

Las ultimas versiones 2.0.x han mejorado la fiabilidad del seguimiento:

- reporte idempotente ante cambios de pagina y reintentos
- un unico temporizador compartido por usuario entre varios navegadores o pestanas
- sincronizacion del temporizador visible con el total autoritativo del servidor

Consulta `changelog.txt` para ver el historial completo.

### Creditos

La version del plugin para Moodle 2.9 y anteriores fue desarrollada por:

- Barbara Debska
- Lukasz Musial
- Lukasz Sanokowski

La actualizacion de la version 1.9 a la 2.5 fue posible gracias a las contribuciones de:

- Classroom Revolution
- Lib Ertea
- Mart van der Niet
- Joseph Thibault

## License

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
