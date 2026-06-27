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
 * Polish language strings for the timestat block.
 *
 * @package    block_timestat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockname'] = 'Timestat';
$string['pluginname'] = 'Timestat';
$string['blocktitle'] = 'Czas polaczony z kursem';
$string['nologs'] = 'Nie znaleziono zadnych logow.';
$string['calculate'] = 'Oblicz';
$string['link'] = 'Oblicz';
$string['viewreport'] = 'Zobacz raport';
$string['summary'] = 'Calkowity czas kursu';
$string['start'] = 'Od:';
$string['end'] = 'Do:';
$string['days'] = ' dni ';
$string['hours'] = ' godzin ';
$string['minuts'] = ' minut ';
$string['seconds'] = ' sekund ';
$string['time'] = 'Czas';
$string['timespent'] = 'Czas spedzony';
$string['choosetimeperiod'] = 'Wybierz okres czasu';
$string['loginterval'] = 'Interwal zapisu (sekundy)';
$string['loginterval_desc'] = 'Interwal czasu, w ktorym aktywnosc uzytkownika jest zapisywana. Minimalna wartosc to 10 sekund.';
$string['inactivitytime'] = 'Czas bezczynnosci (duze ekrany) (sekundy)';
$string['inactivitytime_desc'] = 'Liczba sekund, po ktorych uzytkownik jest uznawany za nieaktywnego. Minimalna wartosc to 10 sekund.';
$string['inactivitytime_small'] = 'Czas bezczynnosci (male ekrany)';
$string['inactivitytime_small_desc'] = 'Liczba sekund, po ktorych uzytkownik jest uznawany za nieaktywnego na malych ekranach. Minimalna wartosc to 10 sekund.';
$string['ignoreinactivity'] = 'Ignoruj bezczynnosc';
$string['ignoreinactivity_desc'] = 'Jesli wlaczone, zliczanie czasu trwa tak dlugo, jak strona pozostaje otwarta, nawet bez interakcji uzytkownika.';
$string['loginterval_help'] = 'Interwal czasu, w ktorym aktywnosc uzytkownika jest zapisywana.';
$string['showtimer'] = 'Pokaz licznik';
$string['showtimer_desc'] = 'Jesli wlaczone, licznik czasu bedzie widoczny dla wszystkich zapisanych uzytkownikow. W przeciwnym razie zobacza go tylko uzytkownicy z uprawnieniem "block/timestat:viewtimer".';
$string['reportedtime'] = 'Zapisany czas';
$string['loading'] = 'Ladowanie...';
$string['notrackinglog'] = 'Nie znaleziono prawidlowego wpisu sledzenia dla tego zadania.';
$string['timestat:viewreport'] = 'Zobacz raport';
$string['timestat:viewtimer'] = 'Zobacz licznik';
$string['timestat:addinstance'] = 'Dodaj nowy blok Timestat';
$string['timestat:view'] = 'Zobacz blok Timestat';
$string['privacy:metadata:block_timestat'] = 'Informacje o czasie spedzonym przez uzytkownika w konkretnym wpisie logu.';
$string['privacy:metadata:block_timestat:log_id'] = 'Identyfikator logu powiazanego z wpisem uzytkownika.';
$string['privacy:metadata:block_timestat:timespent'] = 'Czas spedzony przez uzytkownika w tym wpisie logu.';
$string['selectauser'] = 'Wybierz uzytkownika';
$string['sortby'] = 'Sortuj wedlug';
$string['sort_timespent_desc'] = 'Czas spedzony (od najwiekszego)';
$string['sort_lastname_asc'] = 'Nazwisko';
$string['sort_firstname_asc'] = 'Imie';
$string['trackeditingteachers'] = 'Śledź nauczycieli z uprawnieniami edycji';
$string['trackeditingteachers_desc'] = 'Zezwalaj na śledzenie czasu dla użytkowników z rolą nauczyciela z uprawnieniami edycji.';
$string['trackteachers'] = 'Śledź nauczycieli bez uprawnień edycji';
$string['trackteachers_desc'] = 'Zezwalaj na śledzenie czasu dla użytkowników z rolą nauczyciela bez uprawnień edycji.';
$string['err_min10'] = 'Wartość musi być liczbą większą lub równą 10.';
