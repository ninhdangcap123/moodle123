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
 * Pdf file
 *
 * introduced 15/05/17 23:50
 *
 * @package   local_kopere_dashboard
 * @copyright 2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\report;


/**
 * Class pdf
 *
 * @package local_kopere_dashboard\report
 */
class pdf {
    /**
     * Function create_pdf
     *
     * @throws \coding_exception
     */
    public static function create_pdf() {
        global $CFG, $USER;

        require_once($CFG->libdir . "/tcpdf/tcpdf.php");
        $title = optional_param("title", false, PARAM_TEXT);
        $html = optional_param("html-pdf", false, PARAM_RAW);

        // Crie uma nova instância de TCPDF.
        $pdf = new \TCPDF();

        // Defina as propriedades do PDF.
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(fullname($USER));
        $pdf->SetTitle($title);
        $pdf->SetSubject($title);
        $pdf->SetKeywords("Kopere BI");

        // Remova o cabeçalho e o rodapé padrão.
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Defina a margem da página.
        $pdf->SetMargins(10, 10, 10);

        // Adicione uma página ao PDF.
        $pdf->AddPage("L");

        $style = "
        <style>
            h1 {
                font-size        : 36px;
                font-family      : inherit;
                font-weight      : 500;
                line-height      : 1.1;
                color            : inherit;
                margin           : 20px 0 10px;
                text-align       : center;
            }
            th {
                background-color : #e2e8f0;
                border           : 1px solid #dddddd;
                color            : #5d6a83;
                font-weight      : bold;
                text-align       : center;
                vertical-align   : middle;
            }
            td {
                border           : 1px solid #ddd;
                border-bottom    : none;
                color            : #191b23;
            }
        </style>";

        // Converta o HTML para PDF.
        $pdf->writeHTML("{$style}<h1>{$title}</h1>{$html}", true, false, true, false, "");

        // Saída do PDF para o navegador.
        $pdf->Output("{$title}.pdf", "I");
    }
}
