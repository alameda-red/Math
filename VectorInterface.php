<?php

namespace Alameda\Component\Math;

/**
 * VectorInterface
 *
 * @author Sebastian Kuhlmann <zebba@hotmail.de>
 * @package Alameda\Component\Math
 */
interface VectorInterface
{
    /**
     * @param float $length
     * @param mixed $grid_bearings
     * @return VectorInterface
     */
    public static function createFromGridBearing($length, $grid_bearings);

    /**
     * @param integer $dimension
     * @return float|null
     */
    public function getCoordinate($dimension);

    /**
     * @return float[]
     */
    public function getCoordinates();

    /**
     * @return float
     */
    public function getLength();

    /**
     * @return bool
     */
    public function isNullVector();

    /**
     * @return bool
     */
    public function isUnitVector();

    /**
     * @return int
     */
    public function getSize();

    /**
     * @param VectorInterface $b
     * @return bool
     */
    public function isCollinear(VectorInterface $b);

    /**
     * @return VectorInterface
     */
    public function invert();

    /**
     * @return VectorInterface
     */
    public function getInvertedVector();

    /**
     * @return array
     */
    public function getGridBearings();

    /**
     * @return VectorInterface
     */
    public function add();

    /**
     * @return VectorInterface
     */
    public function getAddedVector();

    /**
     * @param float $scalar
     * @return VectorInterface
     */
    public function scale($scalar);

    /**
     * @param float $scalar
     * @return VectorInterface
     */
    public function getScaledVector($scalar);

    /**
     * @return VectorInterface
     */
    public function normalize();

    /**
     * @return VectorInterface
     */
    public function getNormalizedVector();

    /**
     * @param VectorInterface $v
     * @return float
     */
    public function dotProduct(VectorInterface $v);

    /**
     * @param VectorInterface $v
     * @return float
     */
    public function getAngle(VectorInterface $v);

    /**
     * @param VectorInterface $v
     * @return bool
     */
    public function isOrthogonal(VectorInterface $v);

    /**
     * @param VectorInterface $b
     * @return VectorInterface
     */
    public function crossProduct(VectorInterface $b);

    /**
     * @param VectorInterface $b
     * @param VectorInterface $c
     * @return float
     */
    public function tripleProduct(VectorInterface $b, VectorInterface $c);
}