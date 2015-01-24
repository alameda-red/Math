<?php

namespace Alameda\Component\Math;

interface VectorInterface
{
    public function getCoordinate($dimension);
    public function getCoordinates();
    public function getLength();
}