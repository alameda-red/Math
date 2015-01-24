<?php

namespace Alameda\Math\Tests;

use Alameda\Component\Math\Vector;

/**
 * @author Sebastian Kuhlmann <zebba@hotmail.de>
 * @package Alameda\Component\Math
 */
class VectorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructorArray()
    {
        $v = new Vector(array(1, 2));

        $this->assertEquals(array(1, 2), $v->getCoordinates());
        $this->assertEquals(1, $v->getCoordinate(0));
        $this->assertEquals(2, $v->getCoordinate(1));

        $v = new Vector(array(1, 2, 3));

        $this->assertEquals(array(1, 2, 3), $v->getCoordinates());
        $this->assertEquals(1, $v->getCoordinate(0));
        $this->assertEquals(2, $v->getCoordinate(1));
        $this->assertEquals(3, $v->getCoordinate(2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorOneDimensionalArrayException()
    {
        $v = new Vector(array(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWrongDimensionTypeArrayException()
    {
        $v = new Vector(array(1, 'foo'));
    }

    public function testConstructorMultiArgument()
    {
        $v = new Vector(1, 2);

        $this->assertEquals(array(1, 2), $v->getCoordinates());
        $this->assertEquals(1, $v->getCoordinate(0));
        $this->assertEquals(2, $v->getCoordinate(1));

        $v = new Vector(1, 2, 3);

        $this->assertEquals(array(1, 2, 3), $v->getCoordinates());
        $this->assertEquals(1, $v->getCoordinate(0));
        $this->assertEquals(2, $v->getCoordinate(1));
        $this->assertEquals(3, $v->getCoordinate(2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorSingleArgumentException()
    {
        $v = new Vector(1);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWrongDimensionTypeArgumentException()
    {
        $v = new Vector(1, 'foo');
    }

    public function testConstructorNamedArray()
    {
        $v = new Vector(array('x' => 1, 'y' => 2, 'z' => 3));

        $this->assertEquals(array(1, 2, 3), $v->getCoordinates());
        $this->assertEquals(1, $v->getCoordinate(0));
        $this->assertEquals(2, $v->getCoordinate(1));
        $this->assertEquals(3, $v->getCoordinate(2));
    }

    /**
     * @dataProvider lengthDataProvider
     *
     * @param array $coordinates
     * @param $length
     */
    public function testLength(array $coordinates, $length)
    {
        $v = new Vector($coordinates);

        $this->assertEquals($length, $v->getLength());
    }

    public function lengthDataProvider()
    {
        return array(
            array(array(0, 0, 0), 0.),
            array(array(1, 1, 1), sqrt(3)),
            array(array(2, 2), sqrt(8)),
            array(array(1, 2, 3), sqrt(14)),
        );
    }

    /**
     * @dataProvider nullVectorDataProvider
     *
     * @param array $coordinates
     * @param $is_null
     */
    public function testNullVector(array $coordinates, $is_null)
    {
        $v = new Vector($coordinates);

        $this->assertEquals($is_null, $v->isNullVector());
    }

    public function nullVectorDataProvider()
    {
        return array(
            array(array(0, 0, 0, 0), true),
            array(array(0, 0, 0), true),
            array(array(0, 0), true),
            array(array(1, 1, 1), false),
            array(array(2, 2), false),
            array(array(1, 2, 3), false),
        );
    }

    /**
     * @dataProvider unitVectorDataProvider
     *
     * @param array $coordinates
     * @param $is_unit_vector
     */
    public function testUnitVector(array $coordinates, $is_unit_vector)
    {
        $v = new Vector($coordinates);

        $this->assertEquals($is_unit_vector, $v->isUnitVector());
    }

    public function unitVectorDataProvider()
    {
        return array(
            array(array(0, 0, 1), true),
            array(array(0, 1, 0), true),
            array(array(1, 0, 0), true),
            array(array(1, 1, 0), false),
            array(array(0, 1, 1), false),
            array(array(1, 0, 1), false),
            array(array(1, 1, 1), false),
        );
    }

    /**
     * @dataProvider sizeDataProvider
     *
     * @param array $coordinates
     * @param $size
     */
    public function testSize(array $coordinates, $size)
    {
        $v = new Vector($coordinates);

        $this->assertEquals($size, $v->getSize());
    }

    public function sizeDataProvider()
    {
        return array(
            array(array(1, 1), 2),
            array(array(1, 1, 1), 3),
            array(array(1, 1, 1, 1), 4),
            array(array(1, 1, 1, 1, 1), 5),
        );
    }


    /**
     * @dataProvider collinearDataProvider
     *
     * @param array $a
     * @param array $b
     * @param $is_collinear
     */
    public function testCollinear(array $a, array $b, $is_collinear)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $this->assertEquals($is_collinear, $a->isCollinear($b));
        $this->assertEquals($is_collinear, $b->isCollinear($a));
    }

    public function collinearDataProvider()
    {
        return array(
            array(array(1, 1), array(-1, -1), true),
            array(array(1, 0, 0), array(2, 0, 0), true),
            array(array(0, 1.5, 0), array(0, 2, 0), true),
            array(array(0, 0, 2), array(0, 0, 1), true),
            array(array(1, 1.5, 2.5), array(2, 3, 4), false),
            array(array(1, 1, 1), array(-2, 3, 4), false),
            array(array(1, -1.5, 2.5), array(2, 3, 4), false),
            array(array(1, 1.5, -2.5), array(2, 3, 4), false),

            array(array(0, 1.5, -2.5), array(2, 3, 4), false),
            array(array(1, 0, -2.5), array(2, 3, 4), false),
            array(array(1, 1.5, 0), array(2, 3, 4), false),
        );
    }

    /**
     * @dataProvider invertedVectorDataProvider
     *
     * @param array $coordinates
     * @param array $expected
     */
    public function testInvert(array $coordinates, array $expected)
    {
        $v = new Vector($coordinates);

        $this->assertEquals(new Vector($expected), $v->invert());
    }

    /**
     * @dataProvider invertedVectorDataProvider
     *
     * @param array $coordinates
     * @param array $expected
     */
    public function testInvertedVector(array $coordinates, array $expected)
    {
        $v = new Vector($coordinates);

        $this->assertEquals(new Vector($expected), $v->getInvertedVector());
    }

    public function invertedVectorDataProvider()
    {
        return array(
            array(array(0, 0), array(0, 0)),
            array(array(1, 0), array(-1, 0)),
            array(array(0, 1), array(0, -1)),
            array(array(1, 1), array(-1, -1)),
            array(array(-1, 1), array(1, -1)),
            array(array(1, 2, 3), array(-1, -2, -3)),
        );
    }

    /**
     * @dataProvider gridBearingsDataProvider
     *
     * @param array $coordinates
     * @param array $grid_bearings
     */
    public function testGridBearings(array $coordinates, array $grid_bearings)
    {
        $v = new Vector($coordinates);

        $this->assertEquals($grid_bearings, $v->getGridBearings());
    }

    public function gridBearingsDataProvider()
    {
        return array(
            array(array(4, -2, 5), array(acos(4 / sqrt(45)), acos(-2 / sqrt(45)), acos(5 / sqrt(45)))),
            array(array(0, 0, 0), array(0, 0, 0)),
            array(array(1, 1), array(deg2rad(45), deg2rad(45))),
        );
    }

    /**
     * @dataProvider fromGridBearingDataProvider
     *
     * @param $length
     * @param array $grid_bearings
     * @param array $coordinates
     */
    public function testFromGridBearing($length, array $grid_bearings, array $coordinates)
    {
        $a = Vector::createFromGridBearing($length, $grid_bearings);
        $b = new Vector($coordinates);

        $this->assertEquals($b, $a);
    }

    public function fromGridBearingDataProvider()
    {
        return array(
            array(sqrt(45), array(acos(4 / sqrt(45)), acos(-2 / sqrt(45)), acos(5 / sqrt(45))), array(4, -2, 5)),
        );
    }

    public function testFromGridBearingArguments()
    {
        $a = Vector::createFromGridBearing(sqrt(45), acos(4 / sqrt(45)), acos(-2 / sqrt(45)), acos(5 / sqrt(45)));

        $this->assertEquals(new Vector(4, -2, 5), $a);
    }

    /**
     * @dataProvider addDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $result
     */
    public function testAdd(array $a, array $b, array $result)
    {
        $a = new Vector($a);
        $a->add(new Vector($b));

        $this->assertEquals(new Vector($result), $a);
    }

    public function addDataProvider()
    {
        return array(
            array(array(1, 0), array(0, 1), array(1, 1)),
            array(array(1, 1), array(1, 1), array(2, 2)),
            array(array(1, 1, 1), array(-1, -1, -1), array(0, 0, 0)),
            array(array(1, 1, 0), array(0, 1, 1), array(1, 2, 1)),
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testAddWrongParameter()
    {
        $a = new Vector(array(1, 1));
        $a->add(new \DateTime('now'));
    }

    /**
     * @dataProvider addMultipleDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $c
     * @param array $result
     */
    public function testAddMultiple(array $a, array $b, array $c, array $result)
    {
        $a = new Vector($a);
        $a->add(new Vector($b), new Vector($c));

        $this->assertEquals(new Vector($result), $a);
    }

    public function addMultipleDataProvider()
    {
        return array(
            array(array(1, 0), array(0, 1), array(1, 1), array(2, 2)),
            array(array(1, 1), array(1, 1), array(2, 2), array(4, 4)),
            array(array(1, 1, 1), array(-1, -1, -1), array(0, 0, 0), array(0, 0, 0)),
            array(array(1, 1, 0), array(0, 1, 1), array(1, 2, 1), array(2, 4, 2)),
        );
    }

    /**
     * @dataProvider addMultipleDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $c
     * @param array $result
     */
    public function testAddedVectorMultiple(array $a, array $b, array $c, array $result)
    {
        $a = new Vector($a);
        $added = $a->getAddedVector(new Vector($b), new Vector($c));

        $this->assertEquals(new Vector($result), $added);
    }

    /**
     * @dataProvider scaleDataProvider
     *
     * @param array $a
     * @param $scalar
     * @param $result
     */
    public function testScale(array $a, $scalar, $result)
    {
        $a = new Vector($a);
        $a->scale($scalar);

        $this->assertEquals(new Vector($result), $a);
    }

    public function scaleDataProvider()
    {
        return array(
            array(array(1, 1), 1, array(1, 1)),
            array(array(2, 2), 0.5, array(1, 1)),
            array(array(1, 1, 1), 2, array(2, 2, 2)),
            array(array(2, 2, 2), 2, array(4, 4, 4)),
        );
    }

    public function testScaledVector()
    {
        $a = new Vector(array(1, 1));
        $b = $a->getScaledVector(0.5);

        $this->assertEquals(new Vector(0.5, 0.5), $b);
    }

    /**
     * @@dataProvider normalizeDataProvider
     *
     * @param array $v
     */
    public function testNormalize(array $v)
    {
        $v = new Vector($v);
        $v->normalize();

        $this->assertEquals(1, $v->getLength());
    }

    public function normalizeDataProvider()
    {
        return array(
            array(array(1, 0)),
            array(array(1, 1)),
            array(array(1, 2, 3)),
        );
    }

    /**
     * @dataProvider normalizeDataProvider
     *
     * @param array $v
     */
    public function testNormalizedVector(array $v)
    {
        $v = new Vector($v);
        $b = $v->getNormalizedVector();

        $this->assertEquals(1, $b->getLength());
    }

    /**
     * @dataProvider dotProductDataProvider
     *
     * @param array $a
     * @param array $b
     * @param $dot_product
     */
    public function testDotProduct(array $a, array $b, $dot_product)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $this->assertEquals($dot_product, $a->dotProduct($b));
    }

    public function dotProductDataProvider()
    {
        return array(
            array(array(1, 2, -3), array(5, -1, -5), 18),
        );
    }

    /**
     * @dataProvider angleDataProvider
     *
     * @param array $a
     * @param array $b
     * @param $angle
     */
    public function testAngle(array $a, array $b, $angle)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $this->assertEquals($angle, $a->getAngle($b));
    }

    public function angleDataProvider()
    {
        return array(
            array(array(1, 2, -3), array(5, -1, -5), acos(18 / (sqrt(14) * sqrt(51)))),
        );
    }

    /**
     * @dataProvider orthogonalDataProvider
     *
     * @param array $a
     * @param array $b
     * @param $is_orthogonal
     */
    public function testOrthogonal(array $a, array $b, $is_orthogonal)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $this->assertEquals($is_orthogonal, $a->isOrthogonal($b));
    }

    public function orthogonalDataProvider()
    {
        return array(
            array(array(1, 0), array(0, 1), true),
            array(array(1, 1, 0), array(0, 0, 1), true),
            array(array(1, 2, 3), array(1, 1, 1), false),
        );
    }

    /**
     * @dataProvider crossProductDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $result
     */
    public function testCrossProduct(array $a, array $b, array $result)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $this->assertEquals(new Vector($result), $a->crossProduct($b));
    }

    public function crossProductDataProvider()
    {
        return array(
            array(array(1, 4, 0), array(-2, 5, 3), array(12, -3, 13))
        );
    }

    /**
     * @dataProvider crossProductExceptionDataProvider
     *
     * @expectedException \LogicException
     *
     * @param array $a
     * @param array $b
     */
    public function testCrossProductException(array $a, array $b)
    {
        $a = new Vector($a);
        $b = new Vector($b);

        $a->crossProduct($b);
    }

    public function crossProductExceptionDataProvider()
    {
        return array(
            array(array(0, 0), array(1, 1)),
            array(array(0, 0, 0, 0), array(1, 1, 1, 1)),
        );
    }

    /**
     * @dataProvider tripleProductDataProvider
     *
     * @param array $a
     * @param array $b
     * @param array $c
     * @param $result
     */
    public function testTripleProduct(array $a, array $b, array $c, $result)
    {
        $a = new Vector($a);
        $b = new Vector($b);
        $c = new Vector($c);

        $this->assertEquals($result, $a->tripleProduct($b, $c));
    }

    public function tripleProductDataProvider()
    {
        return array(
            array(array(1, 4, -2), array(-2, 1, -5), array(4, 2, 6), 0)
        );
    }
}