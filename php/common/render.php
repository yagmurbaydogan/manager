<?php

class Renderer
{
    /**
     * @param $dataframe dataframe to render as a table
     * renders a <table></table> using the header and row information stored within a dataframe
     */
    public static function renderTable($dataframe) {
        echo "<table class=\"table\">";
        self::renderTableHeader($dataframe->header);
        self::renderTableRows($dataframe->rows);
        echo "</table>";
    }

    private static function renderTableHeader($header) {
        echo "<thead><tr>";
        foreach ($header as $col) {
            echo "<th scope=\"col\">$col</th>";
        }
        echo "</tr></thead>";
    }

    private static function renderTableRows($rows) {
        foreach ($rows as $row) {
            echo "<tr>";
            foreach ($row as $ele) {
                echo "<td>$ele</td>";
            }
            echo "</tr>";
        }
    }
}