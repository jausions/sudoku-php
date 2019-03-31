<?php

use Abacus11\Games\Sudoku\Grid;
use Abacus11\Games\Sudoku\Solver;
use Abacus11\Games\Sudoku\Zones;

(new Solver(Grid::blank(1), Zones::create(1, 1)))->getSolution();
(new Solver(Grid::blank(2), Zones::create(1, 2)))->getSolution();
(new Solver(Grid::blank(3), Zones::create(1, 3)))->getSolution();
(new Solver(Grid::blank(4), Zones::create(2, 2)))->getSolution();
(new Solver(Grid::blank(5), Zones::create(1, 5)))->getSolution();
(new Solver(Grid::blank(6), Zones::create(3, 2)))->getSolution();
(new Solver(Grid::blank(8), Zones::create(4, 2)))->getSolution();
(new Solver(Grid::blank()))->getSolution();
