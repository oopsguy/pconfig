<?php

namespace pconfig\test\serializer;

use pconfig\serializer\ISerializer;
use PHPUnit\Framework\TestCase;

/**
 * Base serializer test-case class
 * Class BaseSerializerTest
 * @package pconfig\test\serializer
 */
abstract class BaseSerializerTest extends TestCase
{

    /**
     * @var ISerializer
     */
    protected $serializer;

    protected function setUp()
    {
        $this->serializer = $this->targetSerializer();
    }

    /**
     * @return ISerializer
     */
    protected abstract function targetSerializer();

}