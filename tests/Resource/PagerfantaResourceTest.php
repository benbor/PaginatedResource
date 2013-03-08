<?php
/*
* This file is part of the BgyPaginatedResource package.
*
* (c) Boris Guéry <http://borisguery.com/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Bgy\PaginatedResource;

use Bgy\PaginatedResource\Resource\PagerfantaResource;

/**
 * @author Boris Guéry <guery.b@gmail.com>
 */
class PagerfantaResourceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProvider
     * @param array  $data
     * @param string $key
     */
    public function testPagerfantaResource($data, $key, $expected)
    {
        $pagerfanta = $this->getMockBuilder('\Pagerfanta\Pagerfanta')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $pagerfanta
            ->expects($this->any())
            ->method('getNbResults')
            ->will($this->returnValue(count($data)))
        ;
        $pagerfanta
            ->expects($this->any())
            ->method('getNbPages')
            ->will($this->returnValue((int) ceil(count($data) / 3)))
        ;
        $pagerfanta
            ->expects($this->any())
            ->method('getMaxPerPage')
            ->will($this->returnValue(3))
        ;
        $pagerfanta
            ->expects($this->any())
            ->method('getCurrentPage')
            ->will($this->returnValue(1))
        ;
        $pagerfanta
            ->expects($this->any())
            ->method('getMaxPerPage')
            ->will($this->returnValue(3))
        ;
        $pagerfanta
            ->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(array_slice($data, 0, 3, true)))
        ;

        $resource = new PagerfantaResource($pagerfanta, $key);
        $this->assertSame($key, $resource->getDataKey());
        $this->assertEquals(array_slice($data, 0, 3, true), $resource->getData()->toArray());
        $this->assertEquals($expected, $resource->getPaging());
    }

    public static function dataProvider()
    {
        return array(
            array(
                array('Foo', 'Bar', 'Baz', 'Fiz', 'Fuz', 'Faz'),
                'foo',
                new Paging(
                    6,
                    2,
                    3,
                    1,
                    3
                )
            ),
            array(
                range(0, 99),
                'range',
                new Paging(
                    100,
                    34,
                    3,
                    1,
                    3
                )
            )
        );
    }
}
