<?php
namespace Abacus11\Games\Sudoku;

class Grid implements \Countable
{
    /**
     * @var array
     */
    protected $cells;

    public function __construct(array $cells)
    {
        if (!is_array($cells) && !($cells instanceof \ArrayAccess && $cells instanceof \Countable)) {
            throw new \InvalidArgumentException("Not a valid array");
        }
        $count = sqrt(count($cells));
        if ($count != (int)$count) {
            throw new \InvalidArgumentException("The list of values has too few or too many elements");
        }
        $this->cells = (array)$cells;
    }

    /**
     * Creates an empty grid
     *
     * @param int $scale The number of numbers being used for each line, row, or zone. The usual value is 9.
     *
     * @return Grid
     */
    public static function blank($scale = 9)
    {
        return new self(array_fill(0, $scale * $scale, 0));
    }

    /**
     * Creates a grid from a string
     *
     * Zero is to be used where a cell value is not set,
     *
     * @param $string
     *
     * @return Grid
     */
    public static function fromString($string)
    {
        $clean = preg_replace('/\s+/', '', $string);
        return new self(str_split($clean));
    }

    /**
     * @return array
     */
    public function getCells()
    {
        return $this->cells;
    }

    public function getScale()
    {
        return sqrt($this->count());
    }

    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->cells);
    }
}
