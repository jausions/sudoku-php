<?php

namespace Abacus11\Games\Sudoku;

class Zones
{
    /**
     * @var int[]
     */
    protected $cell_zones = [];

    /**
     * @var int[][]
     */
    protected $zone_cells = [];

    /**
     * Zones constructor.
     *
     * @param int[] $cell_zones
     * @param int[][] $zone_cells
     */
    protected function __construct($cell_zones, $zone_cells)
    {
        $this->cell_zones = $cell_zones;
        $this->zone_cells = $zone_cells;
    }

    /**
     * Builds the zones from a list of cell-zone associations
     *
     * @param int[] $cell_zones
     *
     * @return Zones
     * @throws \InvalidArgumentException
     */
    public static function createFromMap($cell_zones)
    {
        $zone_cells = array();
        foreach ($cell_zones as $c => $zone) {
            settype($c, 'integer');
            settype($zone, 'integer');
            if (!isset($zone_cells[$zone])) {
                $zone_cells[$zone] = array();
            }
            $zone_cells[$zone][] = $c;
        }
        $scale = count(reset($zone_cells));
        for ($i = 0; $i < $scale; ++$i) {
            if (!isset($zone_cells[$i])) {
                throw new \InvalidArgumentException("A zone index is missing: " . $i);
            }
            if (count($zone_cells[$i]) != $scale) {
                throw new \InvalidArgumentException("All the zones must have the same surface");
            }
        }
        for ($c = 0; $c < $scale * $scale; ++$c) {
            if (!isset($cell_zones[$c])) {
                throw new \InvalidArgumentException("A cell index is missing: " . $c);
            }
        }

        return new self($cell_zones, $zone_cells);
    }

    /**
     * Creates regular rectangular shaped zones
     *
     * The most common size is 3 x 3.
     *
     * @param int $width Width of a zone
     * @param int $height Height of a zone
     *
     * @return Zones
     */
    public static function create($width, $height)
    {
        $scale = $width * $height;
        $mask = array();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $mask[] = $x + $y * $scale;
            }
        }
        $zone_cells = array();
        for ($zone = 0; $zone < $scale; ++$zone) {
            $x = $width * ($zone % $height);
            $y = floor($zone / $height) * $scale * $height;
            $corner = $x + $y;
            $zone_cells[$zone] = array();
            foreach ($mask as $offset) {
                $zone_cells[$zone][] = $corner + $offset;
            }
        }
        echo "Mask: " . implode(" ", $mask) . PHP_EOL;
        echo "Zones: " . PHP_EOL;
        foreach ($zone_cells as $zone => $cells) {
            echo "  " . $zone . ": " . implode(" ", $cells) . PHP_EOL;
        }
        return new self(self::mapCellToZone($zone_cells), $zone_cells);
    }

    /**
     * Maps the zone to the cells
     *
     * @param int[][] $zones
     *
     * @return int[]
     */
    protected static function mapCellToZone($zones)
    {
        $cell_zones = array();
        foreach ($zones as $zone => $cells) {
            foreach ($cells as $c) {
                $cell_zones[$c] = $zone;
            }
        }
        echo "Cell to zone map: " . PHP_EOL;
        $size = count($cell_zones);
        $scale = sqrt($size);
        for ($i = 0; $i < $size; ++$i) {
            echo $cell_zones[$i] . " " . ((($i + 1) % $scale) ? '' : PHP_EOL);
        }
        return $cell_zones;
    }

    /**
     * @return int[]
     */
    public function getCellToZoneMap()
    {
        return $this->cell_zones;
    }

    /**
     * @return int[][]
     */
    public function getZoneToCellsMap()
    {
        return $this->zone_cells;
    }

    /**
     * @return int
     */
    public function getScale()
    {
        return count(reset($this->zone_cells));
    }
}
