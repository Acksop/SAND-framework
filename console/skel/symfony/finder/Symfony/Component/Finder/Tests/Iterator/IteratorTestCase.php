<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Finder\Tests\Iterator;

abstract class IteratorTestCase extends \PHPUnit_Framework_TestCase
{
    protected function assertIterator($expected, \Traversable $iterator)
    {
        // set iterator_to_array $use_key to false to avoid values merge
        // this made FinderTest::testAppendWithAnArray() failed with GnuFinderAdapter
        $values = array_map(function (\SplFileInfo $fileinfo) {
            return str_replace('/', DIRECTORY_SEPARATOR, $fileinfo->getPathname());
        }, iterator_to_array($iterator, false));

        $expected = array_map(function ($path) {
            return str_replace('/', DIRECTORY_SEPARATOR, $path);
        }, $expected);

        sort($values);
        sort($expected);

        $this->assertEquals($expected, array_values($values));
    }

    protected function assertOrderedIterator($expected, \Traversable $iterator)
    {
        $values = array_map(function (\SplFileInfo $fileinfo) {
            return $fileinfo->getPathname();
        }, iterator_to_array($iterator));

        $this->assertEquals($expected, array_values($values));
    }

    /**
     *  Same as assertOrderedIterator, but checks the order of groups of
     *  array elements.
     *
     *  @param array $expected - an array of arrays. For any two subarrays
     *      $a and $b such that $a goes before $b in $expected, the method
     *      asserts that any element of $a goes before any element of $b
     *      in the sequence generated by $iterator
     *  @param \Traversable $iterator
     */
    protected function assertOrderedIteratorForGroups($expected, \Traversable $iterator)
    {
        $values = array_values(array_map(function (\SplFileInfo $fileinfo) {
            return $fileinfo->getPathname();
        }, iterator_to_array($iterator)));

        foreach ($expected as $subarray) {
            $temp = array();
            while (count($values) && count($temp) < count($subarray)) {
                array_push($temp, array_shift($values));
            }
            sort($temp);
            sort($subarray);
            $this->assertEquals($subarray, $temp);
        }
    }

    /**
     * Same as IteratorTestCase::assertIterator with foreach usage.
     *
     * @param array        $expected
     * @param \Traversable $iterator
     */
    protected function assertIteratorInForeach($expected, \Traversable $iterator)
    {
        $values = array();
        foreach ($iterator as $file) {
            $this->assertInstanceOf('Symfony\\Component\\Finder\\SplFileInfo', $file);
            $values[] = $file->getPathname();
        }

        sort($values);
        sort($expected);

        $this->assertEquals($expected, array_values($values));
    }

    /**
     * Same as IteratorTestCase::assertOrderedIterator with foreach usage.
     *
     * @param array        $expected
     * @param \Traversable $iterator
     */
    protected function assertOrderedIteratorInForeach($expected, \Traversable $iterator)
    {
        $values = array();
        foreach ($iterator as $file) {
            $this->assertInstanceOf('Symfony\\Component\\Finder\\SplFileInfo', $file);
            $values[] = $file->getPathname();
        }

        $this->assertEquals($expected, array_values($values));
    }
}
