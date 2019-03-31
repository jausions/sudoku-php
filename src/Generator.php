<?php

namespace Abacus11\Games\Sudoku;

class Generator
{
    const LEVEL_EASY = 0;
    const LEVEL_MEDIUM = 5;
    const LEVEL_INTERMEDIATE = 10;
    const LEVEL_EXPERT = 15;
    const LEVEL_DEVILISH = 20;

    /**
     * @var Zones
     */
    protected $zones;

    public function __construct(Zones $zones = null)
    {
        $this->zones = ($zones === null) ? Zones::create(3, 3) : $zones;
    }

    public function generate($level = self::LEVEL_INTERMEDIATE)
    {
        $solver = $this->getSolver(Grid::blank($this->zones->getScale()));
        $solution = $solver->getSolution();
    }

    protected function getSolver($init)
    {
        return new Solver($init, $this->zones);
    }
}