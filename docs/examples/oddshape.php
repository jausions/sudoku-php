<?php

use Abacus11\Games\Sudoku\Grid;
use Abacus11\Games\Sudoku\Solver;
use Abacus11\Games\Sudoku\Zones;

$grid_1x1 = array(
    0,
);

$grid_1x2 = array(
    0, 0,
    0, 0,
);

$grid_1x3 = array(
    0, 0, 0,
    0, 0, 0,
    0, 0, 0,
);

$grid_2x2 = array(
    0, 0, 0, 0,
    0, 0, 0, 0,
    0, 0, 0, 0,
    0, 0, 0, 0,
);

$grid_1x5 = array(
    0, 0, 0, 0, 0,
    0, 0, 0, 0, 0,
    0, 0, 0, 0, 0,
    0, 0, 0, 0, 0,
    0, 0, 0, 0, 0,
);

$grid_2x3 = array(
    0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0,
);

$grid_2x4 = array(
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0,
);

$grid_9x9 = array(
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
    0, 0, 0, 0, 0, 0, 0, 0, 0,
);

//(new Sudoku(Grid::blank(1), Zones::create(1, 1)))->solve();
//(new Sudoku(Grid::blank(2), Zones::create(1, 2)))->solve();
//(new Sudoku(Grid::blank(3), Zones::create(1, 3)))->solve();
//(new Sudoku(Grid::blank(4), Zones::create(2, 2)))->solve();
//(new Sudoku(Grid::blank(5), Zones::create(1, 5)))->solve();
//(new Sudoku(Grid::blank(6), Zones::create(3, 2)))->solve();
//(new Sudoku(Grid::blank(8), Zones::create(4, 2)))->solve();
//(new Sudoku(Grid::blank()))->solve();
