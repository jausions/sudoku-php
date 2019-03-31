<?php

namespace Abacus11\Games\Sudoku;

class Solver
{
    /**
     * The grid is represented as a 1-dimension array
     *
     * Top-left cell is at index 0, and the grid is indexed left-to-right
     *
     * @var int[]
     */
    protected $grid;

    /**
     * @var bool
     */
    protected $is_solvable = null;

    /**
     * @var int[]
     */
    protected $solution;

    /**
     * Zone definitions
     *
     * List of cells contained in a zone
     *
     * @var int[][] Indexed by zone ID
     */
    protected $zone_cells = [];

    /**
     * Mapping of cell to its zone
     *
     * @var int[] Indexed by cell grid id
     */
    protected $cell_zone = [];

    /**
     * @var int
     */
    protected $scale;

    /**
     * @var int[]
     */
    protected $numbers;

    /**
     * Sudoku constructor.
     *
     * @param Grid $init Initial state of the grid
     * @param Zones|null $zones Shape of the zones (By default, we use nine 3 x 3 zones)
     */
    public function __construct(Grid $init = null, Zones $zones = null)
    {
        if ($init === null) {
            $init = Grid::blank(9);
        }
        if ($zones === null) {
            $zones = Zones::create(3, 3);
        }
        $this->scale = $zones->getScale();
        $size = $this->scale * $this->scale;
        if (count($init) != $size) {
            throw new \InvalidArgumentException("Data incompatible with dimensions");
        }
        $this->grid = $init->getCells();
        array_walk($this->grid, function ($value, $key) use ($size) {
            $int = intval($value, 10);
            if ($int < 0 || $int > $this->scale) {
                throw new \InvalidArgumentException("Invalid value " . $value . " at position " . $key);
            }
            if (!is_int($key)) {
                throw new \InvalidArgumentException("Array must be indexed with integers");
            }
            if ($key < 0 || $key >= $size) {
                throw new \InvalidArgumentException("Invalid index. Must be between 0 and " . ($size - 1));
            }
        });
        $this->numbers = array();
        for ($i = 1; $i <= $this->scale; ++$i) {
            $this->numbers[$i] = $i;
        }
        $this->zone_cells = $zones->getZoneToCellsMap();
        $this->cell_zone = $zones->getCellToZoneMap();
    }

    /**
     * Return the index of the start of the row for the given element
     *
     * @param int $cell 0-based index
     *
     * @return int
     */
    protected function row($cell)
    {
        return floor($cell / $this->scale);
    }

    /**
     * Return the index of the start of the column for the given element
     *
     * @param int $cell 0-based index
     *
     * @return int
     */
    protected function column($cell)
    {
        return $cell % $this->scale;
    }

    /**
     * Return the zone id containing the given cell
     *
     * @param int $cell
     *
     * @return int Zone id
     */
    protected function zone($cell)
    {
        return $this->cell_zone[$cell];
    }

    /**
     * @param int $number
     * @param int $row
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isNotInRow($number, $row, &$grid)
    {
        $y = $row * $this->scale;
        for ($x = 0; $x < $this->scale; ++$x) {
            if ($grid[$y + $x] == $number) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $number
     * @param int $column
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isNotInColumn($number, $column, &$grid)
    {
        for ($y = 0; $y < $this->scale; ++$y) {
            if ($grid[$column + $this->scale * $y] == $number) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $number
     * @param int $zone
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isNotInZone($number, $zone, &$grid)
    {
        foreach ($this->zone_cells[$zone] as $c) {
            if ($grid[$c] == $number) {
                return false;
            }
        }
        return true;
    }

    /**
     * Checks if a number is possible for a given cell
     *
     * @param int $number
     * @param int $cell
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isPossibleForCell($number, $cell, &$grid)
    {
        $row = $this->row($cell);
        $column = $this->column($cell);
        $zone = $this->zone($cell);
        return $this->isNotInRow($number, $row, $grid)
            && $this->isNotInColumn($number, $column, $grid)
            && $this->isNotInZone($number, $zone, $grid);
    }

    /**
     * Checks if the row is solved
     *
     * @param int $row
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isSolvedRow($row, &$grid)
    {
        $left = $this->numbers;
        $y = $row * $this->scale;
        for ($x = 0; $x < $this->scale; ++$x) {
            unset($left[$grid[$y + $x]]);
        }
        return count($left) == 0;
    }

    /**
     * Checks if the column is solved
     *
     * @param int $col
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isSolvedColumn($col, &$grid)
    {
        $left = $this->numbers;
        for ($y = 0; $y < $this->scale; ++$y) {
            unset($left[$grid[$col + $y * $this->scale]]);
        }
        return count($left) == 0;
    }

    /**
     * Checks if the zone is solved
     *
     * @param int $zone
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isSolvedZone($zone, &$grid)
    {
        $left = $this->numbers;
        foreach ($this->zone_cells[$zone] as $c) {
            unset($left[$grid[$c]]);
        }
        return count($left) == 0;
    }

    /**
     * Checks if the grid is solved
     *
     * @param int[] &$grid
     *
     * @return bool
     */
    protected function isSolved(&$grid)
    {
        for ($i = 0; $i < $this->scale; ++$i) {
            if (!$this->isSolvedZone($i, $grid)
                || !$this->isSolvedRow($i, $grid)
                || !$this->isSolvedColumn($i, $grid)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param int $cell
     * @param int[] $grid
     *
     * @return int[] array
     */
    protected function getCandidatesForCell($cell, &$grid)
    {
        $candidates = array();
        for ($number = 1; $number <= $this->scale; ++$number) {
            if ($this->isPossibleForCell($number, $cell, $grid)) {
                $candidates[] = $number;
            }
        }
        return $candidates;
    }

    protected function pickRandomCandidateForCell(&$candidates, $cell)
    {
        return $candidates[$cell][array_rand($candidates[$cell])];
    }

    protected function getCandidates(&$grid)
    {
        $candidates = array();
        foreach ($grid as $i => $number) {
            if ($number != 0) {
                $candidates[$i] = array();
            } else {
                $candidates[$i] = $this->getCandidatesForCell($i, $grid);
                if (count($candidates[$i]) == 0) {
                    return false;
                }
            }
        }
        return $candidates;
    }

    protected function removeCandidate($attempt_array, $number)
    {
        $new_array = array();
        for ($x = 0; $x < count($attempt_array); $x++) {
            if ($attempt_array[$x] != $number) {
                array_unshift($new_array, $attempt_array[$x]);
            }
        }
        return $new_array;
    }

    protected function getCellWithFewestCandidates(&$candidates)
    {
        $max = $this->scale;
        $cell = null;
        foreach ($candidates as $i => $cell_candidates) {
            $count = count($cell_candidates);
            if ($count > 0 && $count <= $max) {
                $max = $count;
                $cell = $i;
            }
        }
        return $cell;
    }

    /**
     * Checks if the puzzle is solvable
     *
     * @return bool
     */
    public function isSolvable()
    {
        if ($this->is_solvable === null) {
            $result = $this->solve();
            if ($result === false) {
                $this->is_solvable = false;
            } else {
                $this->solution = $result;
            }
        }
        return $this->is_solvable;
    }

    /**
     * Get one of the puzzle's solutions
     *
     * @return int[]|null A solution or NULL if the puzzle is not solvable
     */
    public function getSolution()
    {
        return ($this->isSolvable()) ? $this->solution : null;
    }

    /**
     * Attempts to solve the puzzle
     *
     * @return Grid|bool Solution grid if solved, FALSE otherwise
     */
    protected function solve()
    {
        if ($this->is_solvable === false) {
            return false;
        }
        $stack = array();
        $grid = $this->grid;
        while (!$this->isSolved($grid)) {
            $candidates = $this->getCandidates($grid);
            if ($candidates == false) {
                if (empty($stack)) {
                    // We ran out of options
                    $this->is_solvable = false;
                    return false;
                }
                // Attempt failed, backtrack to previous state
                list($grid, $candidates) = array_pop($stack);
            }
            $cell = $this->getCellWithFewestCandidates($candidates);
            $number = $this->pickRandomCandidateForCell($candidates, $cell);
            // Save the current state in case we have to try another number
            if (count($candidates[$cell]) > 1) {
                $candidates[$cell] = $this->removeCandidate($candidates[$cell], $number);
                $stack[] = array($grid, $candidates);
            }
            $grid[$cell] = $number;
        }
        return new Grid($grid);
    }
}
