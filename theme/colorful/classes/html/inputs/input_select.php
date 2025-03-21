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
 * input_select file
 *
 * introduced 10/06/17 20:42
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

/**
 * Class input_select
 *
 * @package local_kopere_dashboard\html\inputs
 */
class input_select extends input_base {
    /** @var array */
    private $values;
    /** @var string */
    private $valueskey;
    /** @var string */
    private $valuesvalue;

    /** @var bool */
    private $addselect = false;

    /**
     * input_select constructor.
     */
    public function __construct() {
        $this->set_type("select");
    }

    /**
     * Function new_instance
     *
     * @return input_select
     */
    public static function new_instance() {
        return new input_select();
    }

    /**
     * Function get_values
     *
     * @return array
     */
    public function get_values() {
        return $this->values;
    }

    /**
     * Function set_values
     *
     * @param $values
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function set_values($values, $key = "key", $value = "value") {
        $this->values = $values;
        $this->valueskey = $key;
        $this->valuesvalue = $value;

        return $this;
    }

    /**
     * Function set_add_select
     *
     * @param $addselect
     *
     * @return $this
     */
    public function set_add_select($addselect) {
        $this->addselect = $addselect;

        return $this;
    }

    /**
     * Function to_string
     *
     * @return mixed|string
     * @throws \coding_exception
     */
    public function to_string() {
        $return = "<select id='{$this->inputid}' name='{$this->name}' ";

        if ($this->class) {
            $return .= "class='{$this->class}' ";
        }

        if ($this->style) {
            $return .= "style='{$this->style}' ";
        }

        $return .= $this->extras;

        $return .= ">";

        if ($this->addselect) {
            $return .= "\n\t<option value=''>..:: " . get_string("select", "local_kopere_dashboard") . " ::..</option>";
        }

        foreach ($this->values as $row) {
            $extra = "";
            if (is_array($row)) {
                $key = $row[$this->valueskey];
                $value = $row[$this->valuesvalue];

                if (isset($row["disableselect"]) && $row["disableselect"]) {
                    $extra = ' disabled="disabled"';
                }
            } else {
                $key = $row->{$this->valueskey};
                $value = $row->{$this->valuesvalue};

                if (isset($row->disableselect) && $row->disableselect) {
                    $extra = ' disabled="disabled"';
                }
            }

            $return .= "\n\t<option value='{$key}'";
            if ($key == $this->value) {
                $return .= ' selected="selected"';
            }

            $return .= "{$extra}>";
            $value = preg_replace('/\s/', '&nbsp;', htmlentities($value, ENT_COMPAT));
            $return .= "{$value}</option>";
        }

        $return .= "\n</select>";

        return $return;
    }
}
