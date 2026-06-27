# Timestat block for Moodle

Timestat is a Moodle block that measures active time spent by users inside a course.

## Requirements

- Moodle 3.11 or later
- Plugin release: 2.0.17

## Installation

Install the block in the standard Moodle way:

1. Copy the plugin to `blocks/timestat`.
2. Visit `Site administration > Notifications`.

You can also install it from the Moodle administration UI as a ZIP package.

## What it does

- Tracks active time on pages where the block is available.
- Pauses tracking after a configurable inactivity period based on browser activity.
- Can optionally ignore inactivity and keep counting while the page stays open.
- Shows an optional visual timer with the accumulated course time.
- Provides a course report with filters for user, group, date range and activity.
- Supports CSV and Excel exports from the report page.
- Keeps one authoritative course total per user even when several tabs or browser windows are open.
- Prevents duplicate counting caused by overlapping requests, page changes or retries.

## How tracking works

The block only tracks time on pages where it has been added. If you want the block to be available on the course page and activity pages, make it sticky throughout the course:

https://docs.moodle.org/400/en/Block_settings#Making_a_block_sticky_throughout_a_course

For quiz attempt pages, enable `Show blocks during the attempt` in the quiz appearance settings.

Time can be tracked even when the visual counter is not shown to the learner. The visible timer is synchronized with the server-side course total so that inactive tabs stay up to date while another tab remains active.

## Permissions

Main capabilities:

- `block/timestat:view`: allows the block and tracking logic to be used in the course.
- `block/timestat:viewreport`: allows access to the detailed report.
- `block/timestat:viewtimer`: allows viewing the visual timer when it is not globally enabled.
- `block/timestat:addinstance`: allows adding the block to a page.

By default, students can be tracked, while report access is limited to teaching and management roles.

## Configuration

Plugin settings are available at `Site administration > Plugins > Blocks > Timestat`.

Current settings include:

- `Show timer`
- `Log interval`
- `Inactivity time (big screens)`
- `Inactivity time (small screens)`
- `Ignore inactivity`
- `Track editing teachers`
- `Track teachers`

## Stored data and privacy

The plugin stores:

- time spent records linked to tracked log entries
- browser session state used to avoid duplicate counting
- shared per-user, per-course tracking state used to merge simultaneous browser sessions

Privacy metadata is implemented in `classes/privacy/provider.php`.

## Recent changes

Recent 2.0.x updates improved tracking reliability:

- idempotent reporting across page changes and request retries
- one shared course timer per user across multiple browsers or tabs
- synchronization of the visible timer with the authoritative server total

See `changelog.txt` for the full history.

## Credits

The version of the plugin for Moodle 2.9 and earlier was developed by:

- Barbara Debska
- Lukasz Musial
- Lukasz Sanokowski

Upgrade from 1.9 to 2.5 was made thanks to contributions from:

- Classroom Revolution
- Lib Ertea
- Mart van der Niet
- Joseph Thibault

## License

Licensed under the [GNU GPL License](http://www.gnu.org/copyleft/gpl.html).
