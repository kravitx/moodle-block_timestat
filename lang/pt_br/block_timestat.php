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
 * Cadeias em portugues do Brasil para o bloco Timestat.
 *
 * @package    block_timestat
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['blockname'] = 'Timestat';
$string['pluginname'] = 'Timestat';
$string['blocktitle'] = 'Tempo conectado ao curso';
$string['nologs'] = 'Nenhum registro encontrado.';
$string['calculate'] = 'Calcular';
$string['viewreport'] = 'Ver relatorio';
$string['summary'] = 'Tempo total do curso';
$string['start'] = 'Inicio:';
$string['end'] = 'Fim:';
$string['days'] = ' dias ';
$string['hours'] = ' horas ';
$string['minuts'] = ' minutos ';
$string['seconds'] = ' segundos ';
$string['time'] = 'Tempo';
$string['timespent'] = 'Tempo gasto';
$string['choosetimeperiod'] = 'Escolher periodo';
$string['loginterval'] = 'Intervalo de registro (segundos)';
$string['loginterval_desc'] = 'Intervalo de tempo em que a atividade do usuario e registrada. O valor minimo e 10 segundos.';
$string['inactivitytime'] = 'Tempo de inatividade (telas grandes) (segundos)';
$string['inactivitytime_desc'] = 'Tempo em segundos apos o qual o usuario e considerado inativo. O valor minimo e 10 segundos.';
$string['inactivitytime_small'] = 'Tempo de inatividade (telas pequenas)';
$string['inactivitytime_small_desc'] = 'Tempo em segundos apos o qual o usuario e considerado inativo em telas pequenas. O valor minimo e 10 segundos.';
$string['ignoreinactivity'] = 'Ignorar inatividade';
$string['ignoreinactivity_desc'] = 'Se ativado, a contagem de tempo continua enquanto a pagina permanecer aberta, mesmo sem interacao do usuario.';
$string['loginterval_help'] = 'Intervalo de tempo em que a atividade do usuario e registrada.';
$string['showtimer'] = 'Mostrar temporizador';
$string['showtimer_desc'] = 'Se ativado, o contador de tempo sera visivel para todos os usuarios matriculados. Se desativado, sera visivel apenas para usuarios com a capacidade "block/timestat:viewtimer".';
$string['reportedtime'] = 'Tempo registrado';
$string['loading'] = 'Carregando...';
$string['notrackinglog'] = 'Nenhum registro de rastreamento valido foi encontrado para esta solicitacao.';
$string['timestat:viewreport'] = 'Ver relatorio';
$string['timestat:viewtimer'] = 'Ver temporizador';
$string['timestat:addinstance'] = 'Adicionar um novo bloco Timestat';
$string['timestat:view'] = 'Ver o bloco Timestat';
$string['privacy:metadata:block_timestat'] = 'Informacoes sobre o tempo gasto pelo usuario em uma entrada especifica de log.';
$string['privacy:metadata:block_timestat:log_id'] = 'O ID do log relacionado a entrada de log do usuario.';
$string['privacy:metadata:block_timestat:timespent'] = 'O tempo gasto pelo usuario na entrada de log.';
$string['selectauser'] = 'Selecionar um usuario';
$string['sortby'] = 'Ordenar por';
$string['sort_timespent_desc'] = 'Tempo gasto (maior para menor)';
$string['sort_lastname_asc'] = 'Sobrenome';
$string['sort_firstname_asc'] = 'Nome';
$string['trackeditingteachers'] = 'Rastrear professores com permissão de edição';
$string['trackeditingteachers_desc'] = 'Permitir o registro do tempo para usuários com o papel de professor com permissão de edição.';
$string['trackteachers'] = 'Rastrear professores sem permissão de edição';
$string['trackteachers_desc'] = 'Permitir o registro do tempo para usuários com o papel de professor sem permissão de edição.';
$string['err_min10'] = 'O valor deve ser um número maior ou igual a 10.';
