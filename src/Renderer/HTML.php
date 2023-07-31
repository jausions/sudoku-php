<?php

namespace Abacus11\Games\Sudoku\Renderer;

use Abacus11\Games\Sudoku\Grid;
use Abacus11\Games\Sudoku\Renderer;
use Abacus11\Games\Sudoku\Zones;

class HTML implements Renderer
{
    public function render(Grid $grid, Zones $zones)
    {
        $cells = $grid->getCells();
        $scale = $grid->getScale();

        $cell_to_zone = $zones->getCellToZoneMap();

        $html = '<table>' . PHP_EOL;
        for ($x = 0; $x < $scale; ++$x) {
            $html .= '<tr>';
            for ($y = 0; $y < $scale; ++$y) {
                $c = $x * $scale + $y;
                $html .= '<td class="sudoku-zone-' . $cell_to_zone[$c] . '"><span>' . $cells[$c] . "</span></td>";
            }
            $html .= '</tr>' . PHP_EOL;
        }
        return $html . '</table>' . PHP_EOL;
    }
}