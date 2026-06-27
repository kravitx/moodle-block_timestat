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
 * Chaines francaises pour le bloc Timestat.
 *
 * @package    block_timestat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockname'] = 'Timestat';
$string['pluginname'] = 'Timestat';
$string['blocktitle'] = 'Temps connecte au cours';
$string['nologs'] = 'Aucun journal trouve.';
$string['calculate'] = 'Calculer';
$string['viewreport'] = 'Voir le rapport';
$string['summary'] = 'Temps total du cours';
$string['start'] = 'Debut :';
$string['end'] = 'Fin :';
$string['days'] = ' jours ';
$string['hours'] = ' heures ';
$string['minuts'] = ' minutes ';
$string['seconds'] = ' secondes ';
$string['time'] = 'Temps';
$string['timespent'] = 'Temps passe';
$string['choosetimeperiod'] = 'Choisir une periode';
$string['loginterval'] = 'Intervalle de journalisation (secondes)';
$string['loginterval_desc'] = 'Intervalle de temps auquel l activite de l utilisateur est enregistree. La valeur minimale est de 10 secondes.';
$string['inactivitytime'] = 'Temps d inactivite (grands ecrans) (secondes)';
$string['inactivitytime_desc'] = 'Temps en secondes apres lequel l utilisateur est considere comme inactif. La valeur minimale est de 10 secondes.';
$string['inactivitytime_small'] = 'Temps d inactivite (petits ecrans)';
$string['inactivitytime_small_desc'] = 'Temps en secondes apres lequel l utilisateur est considere comme inactif sur les petits ecrans. La valeur minimale est de 10 secondes.';
$string['ignoreinactivity'] = 'Ignorer l inactivite';
$string['ignoreinactivity_desc'] = 'Si cette option est activee, le suivi du temps continue tant que la page reste ouverte, meme si l utilisateur n interagit pas avec elle.';
$string['loginterval_help'] = 'Intervalle de temps auquel l activite de l utilisateur est enregistree.';
$string['showtimer'] = 'Afficher le minuteur';
$string['showtimer_desc'] = 'Si cette option est activee, le compteur de temps sera visible par tous les utilisateurs inscrits. Sinon, il ne sera visible que par les utilisateurs ayant la capacite "block/timestat:viewtimer".';
$string['reportedtime'] = 'Temps enregistre';
$string['loading'] = 'Chargement...';
$string['notrackinglog'] = 'Aucun enregistrement de suivi valide n a ete trouve pour cette requete.';
$string['timestat:viewreport'] = 'Voir le rapport';
$string['timestat:viewtimer'] = 'Voir le minuteur';
$string['timestat:addinstance'] = 'Ajouter un nouveau bloc Timestat';
$string['timestat:view'] = 'Voir le bloc Timestat';
$string['privacy:metadata:block_timestat'] = 'Informations sur le temps passe par l utilisateur dans une entree de journal specifique.';
$string['privacy:metadata:block_timestat:log_id'] = 'Identifiant du journal lie a l entree de journal de l utilisateur.';
$string['privacy:metadata:block_timestat:timespent'] = 'Temps passe par l utilisateur dans l entree de journal.';
$string['selectauser'] = 'Selectionner un utilisateur';
$string['sortby'] = 'Trier par';
$string['sort_timespent_desc'] = 'Temps passe (du plus eleve au plus faible)';
$string['sort_lastname_asc'] = 'Nom de famille';
$string['sort_firstname_asc'] = 'Prenom';
$string['trackeditingteachers'] = 'Suivre les enseignants éditeurs';
$string['trackeditingteachers_desc'] = 'Autoriser le suivi du temps pour les utilisateurs ayant le rôle d\'enseignant éditeur.';
$string['trackteachers'] = 'Suivre les enseignants non éditeurs';
$string['trackteachers_desc'] = 'Autoriser le suivi du temps pour les utilisateurs ayant le rôle d\'enseignant non éditeur.';
$string['err_min10'] = 'La valeur doit être un nombre supérieur ou égal à 10.';
