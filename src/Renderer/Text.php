<?php

namespace Abacus11\Games\Sudoku\Renderer;

use Abacus11\Games\Sudoku\Grid;
use Abacus11\Games\Sudoku\Renderer;
use Abacus11\Games\Sudoku\Zones;

class Text implements Renderer
{
    public function render(Grid $grid, Zones $zones)
    {
        $cells = $grid->getCells();
        $scale = $grid->getScale();
        $s = '';
        for ($x = 0; $x < $scale; ++$x) {
            for ($y = 0; $y < $scale; ++$y) {
                $s .= $cells[$x * $scale + $y] . " ";
            }
            $s .= PHP_EOL;
        }
        return $s;
    }
}