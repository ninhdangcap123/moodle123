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
 * Introduced  10/04/2020 18:05
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

use local_kopere_dashboard\html\data_table;
use local_kopere_dashboard\html\form;
use local_kopere_dashboard\html\inputs\input_select;
use local_kopere_dashboard\html\table_header_item;
use local_kopere_dashboard\util\dashboard_util;
use local_kopere_dashboard\util\datatable_search_util;
use local_kopere_dashboard\util\url_util;

/**
 * Class useraccess
 *
 * @package local_kopere_dashboard
 */
class useraccess {

    /**
     * Function dashboard
     *
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function dashboard() {
        global $PAGE;

        dashboard_util::add_breadcrumb(get_string("useraccess_title", "local_kopere_dashboard"));
        dashboard_util::start_page();

        echo '<div class="element-box bloco_changue_mes">';
        $changuemes = optional_param("changue_mes", date("Y-m"), PARAM_TEXT);
        $form = new form(url_util::makeurl("useraccess", "dashboard"));
        $form->add_input(input_select::new_instance()
            ->set_title(get_string("select_month", "local_kopere_dashboard"))
            ->set_name("changue_mes")
            ->set_values($this->list_meses())
            ->set_value($changuemes));
        $form->close();
        echo "</div>";

        echo '<div class="element-box">';
        $table = new data_table();
        $table->add_header("#", "userid", table_header_item::TYPE_INT, null, "width: 20px");
        $table->add_header(get_string("user_table_fullname", "local_kopere_dashboard"), "fullname");
        $table->add_header(get_string("user_table_email", "local_kopere_dashboard"), "email");
        $table->add_header(get_string("user_table_phone", "local_kopere_dashboard"), "phone1");
        $table->add_header(get_string("user_table_celphone", "local_kopere_dashboard"), "phone2");
        $table->add_header(get_string("user_table_city", "local_kopere_dashboard"), "city");

        $table->set_ajax_url(url_util::makeurl("useraccess", "load_all_users", ["changue_mes" => $changuemes]));
        $table->set_click_redirect(url_util::makeurl("users", "details", ["userid" => "{userid}"]), "userid");
        $table->print_header();
        $table->close(true, ["order" => [[1, "asc"]]]);

        echo "</div>";

        $PAGE->requires->js_call_amd("local_kopere_dashboard/useraccess", "useraccess_changue_mes");
        echo "<style>.bloco_changue_mes{display:none}</style>";

        dashboard_util::end_page();
    }

    /**
     * Function load_all_users
     *
     * @throws \coding_exception
     * @throws \dml_exception
     * @throws \Exception
     */
    public function load_all_users() {
        global $DB;

        $changuemes = required_param("changue_mes", PARAM_TEXT);

        $columns = [
            "userid",
            "firstname",
            "username",
            "email",
            "phone1",
            "phone2",
            "city",
            "lastname",
        ];
        $search = new datatable_search_util($columns);

        if ($DB->get_dbfamily() == "mysql") {
            $where = "date_format( from_unixtime(l.timecreated), '%Y-%m' ) LIKE :changuemes";
        } else {
            throw new \Exception("only mysqli");
        }

        $search->execute_sql_and_return("
               SELECT {[columns]}
                 FROM {logstore_standard_log} l
                 JOIN {user}                  u ON l.userid = u.id
                WHERE action LIKE 'loggedin'
                  AND {$where}
            ", 'GROUP BY l.userid',
            ["changuemes" => $changuemes],
            "\\local_kopere_dashboard\\util\\user_util::column_fullname");
    }

    /**
     * Function list_meses
     *
     * @return array
     */
    private function list_meses() {
        $ultimosmeses = [];
        $ano = date("Y");
        $mes = date("m");
        for ($i = 0; $i < 24; $i++) {
            if ($mes < 10) {
                $mes = "0" . intval($mes);
            }
            $ultimosmeses[] = ["key" => "{$ano}-{$mes}", "value" => "{$mes} / {$ano}"];
            $mes--;
            if ($mes == 0) {
                $ano--;
                $mes = 12;
            }
        }

        return $ultimosmeses;
    }
}
