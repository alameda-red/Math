<?php

namespace Alameda\Component\Math;

use Zebba\Component\Utility\ParameterConverter;

/**
 * Vector
 *
 * @author Sebastian Kuhlmann <zebba@hotmail.de>
 * @package Alameda\Component\Math
 */
class Vector implements VectorInterface
{
    /** @var float[] */
    private $coordinates;

    /**
     * @param float $length
     * @param mixed $grid_bearings
     * @return VectorInterface
     */
    public static function createFromGridBearing($length, $grid_bearings)
    {
        if (is_array($grid_bearings)) {
            $bearings = array_values($grid_bearings);
        } else {
            $bearings = func_get_args();
            $length = $bearings[0];

            unset($bearings[0]); // length

            $bearings = array_values($bearings);
        }

        $coordinates = array_map(function ($e) use ($length) {
            return $length * cos($e);
        }, $bearings);

        return new Vector($coordinates);
    }

    /**
     * @param mixed $coordinates
     */
    public function __construct($coordinates)
    {
        if (is_array($coordinates)) {
            if (count($coordinates) < 2) { throw new \InvalidArgumentException('A vector needs to have at least 2 dimensions.'); }

            $only_numbers = array_reduce($coordinates, function ($carry, $e) {
                if (! is_numeric($e)) { $carry = false; }

                return $carry;
            }, true);

            if (! $only_numbers) { throw new \InvalidArgumentException('A vector can only comprise of numbers.'); }

            $this->coordinates = array_values($coordinates);
        } else {
            $coords = func_get_args();

            if (count($coords) < 2) { throw new \InvalidArgumentException('A vector needs to have at least 2 dimensions.'); }

            $only_numbers = array_reduce($coords, function ($carry, $e) {
                if (! is_numeric($e)) { $carry = false; }

                return $carry;
            }, true);

            if (! $only_numbers) { throw new \InvalidArgumentException('A vector can only comprise of numbers.'); }

            $this->coordinates = array_values($coords);
        }
    }

    /**
     * @param integer $dimension
     * @return float|null
     */
    public function getCoordinate($dimension)
    {
        return (array_key_exists($dimension, $this->coordinates)) ? $this->coordinates[$dimension] : null;
    }

    /**
     * @return float[]
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        $coordinates = array_map(function ($e) { return pow($e, 2); }, $this->coordinates);

        return sqrt(array_sum($coordinates));
    }

    /**
     * @return bool
     */
    public function isNullVector()
    {
        return (0. === $this->getLength());
    }

    /**
     * @return bool
     */
    public function isUnitVector()
    {
        return (1. === $this->getLength());
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return count($this->coordinates);
    }

    /**
     * @param VectorInterface $b
     * @return bool
     */
    public function isCollinear(VectorInterface $b)
    {
        if ($this->getSize() !== $b->getSize()) { throw new \InvalidArgumentException('The vectors should have the same size.'); }

        $scalar = null;

        for ($i = 0; $i < count($this->coordinates); $i++) {
            try {
                $x = $b->getCoordinate($i) / $this->coordinates[$i];
            } catch (\Exception $e) {
                continue;
            }

            if (is_null($scalar)) {
                $scalar = $x;
            } else {
                if ($scalar !== $x) { return false; }
            }
        }

        return true;
    }

    /**
     * @return VectorInterface
     */
    public function invert()
    {
        $this->coordinates = array_map(function ($e) { return (-1) * $e; }, $this->coordinates);

        return $this;
    }

    /**
     * @return VectorInterface
     */
    public function getInvertedVector()
    {
        $v = clone $this;

        return $v->invert();
    }

    /**
     * @return array
     */
    public function getGridBearings()
    {
        $length = $this->getLength();

        if (0. === $length) {
            return array_fill(0, count($this->coordinates), 0.);
        }

        return array_map(function ($e) use ($length) { return acos($e / $length); }, $this->coordinates);
    }

    /**
     * @return VectorInterface
     */
    public function add()
    {
        $vectors = func_get_args();

        try {
            $vectors = ParameterConverter::toArray($vectors, 'Alameda\Component\Math\VectorInterface');
        } catch (\DomainException $e) {
            throw $e;
        }

        foreach ($vectors as $v) { /* @var $v VectorInterface */
            if ($this->getSize() !== $v->getSize()) { throw new \InvalidArgumentException('The vectors need to have the same amount of dimensions.'); }

            foreach ($v->getCoordinates() as $d => $c) {
                $this->coordinates[$d] += $c;
            }
        }

        return $this;
    }

    /**
     * @return VectorInterface
     */
    public function getAddedVector()
    {
        $a = clone $this;

        foreach(func_get_args() as $b) { /* @var $a VectorInterface */
            $a->add($b);
        }

        return $a;
    }

    /**
     * @param float $scalar
     * @return VectorInterface
     */
    public function scale($scalar)
    {
        if (! is_numeric($scalar)) { throw new \InvalidArgumentException('The scalar value needs to be numeric.'); }

        $this->coordinates = array_map(function ($e) use ($scalar) { return $e * $scalar; }, $this->coordinates);

        return $this;
    }

    /**
     * @param float $scalar
     * @return VectorInterface
     */
    public function getScaledVector($scalar)
    {
        $v = clone $this;

        return $v->scale($scalar);
    }

    /**
     * @return VectorInterface
     */
    public function normalize()
    {
        $length = $this->getLength();

        if (0. !== $length) {
            $this->coordinates = array_map(function ($e) use ($length) {
                return $e / $length;
            }, $this->coordinates);
        }

        return $this;
    }

    /**
     * @return VectorInterface
     */
    public function getNormalizedVector()
    {
        $v = clone $this;

        return $v->normalize();
    }

    /**
     * @param VectorInterface $v
     * @return float
     */
    public function dotProduct(VectorInterface $v)
    {
        if ($this->getSize() !== $v->getSize()) { throw new \InvalidArgumentException('The scalar value needs to be numeric.'); }

        $result = 0;

        foreach ($v->getCoordinates() as $d => $c) {
            $result += $this->coordinates[$d] * $c;
        }

        return $result;
    }

    /**
     * @param VectorInterface $v
     * @return float
     */
    public function getAngle(VectorInterface $v)
    {
        return acos($this->dotProduct($v) / ($this->getLength() * $v->getLength()));
    }

    /**
     * @param VectorInterface $v
     * @return bool
     */
    public function isOrthogonal(VectorInterface $v)
    {
        return (0 === $this->dotProduct($v));
    }

    /**
     * @param VectorInterface $b
     * @return VectorInterface
     */
    public function crossProduct(VectorInterface $b)
    {
        if (3 !== $this->getSize() || 3 !== $b->getSize()) {
            throw new \LogicException('The cross product can currently only be calculated in 3-dimensional space.');
        }

        $a = $this->coordinates;
        $b = $b->getCoordinates();

        $coordinates = array($a[1] * $b[2] - $a[2] * $b[1], $a[2] * $b[0] - $a[0] * $b[2], $a[0] * $b[1] - $a[1] * $b[0]);

        return new self($coordinates);
    }

    /**
     * @param VectorInterface $b
     * @param VectorInterface $c
     * @return float
     */
    public function tripleProduct(VectorInterface $b, VectorInterface $c)
    {
        return $this->dotProduct(($b->crossProduct($c)));
    }
}