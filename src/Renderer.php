<?php

namespace Abacus11\Games\Sudoku;

interface Renderer
{
    public function render(Grid $grid, Zones $zones);
}