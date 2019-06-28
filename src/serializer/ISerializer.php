<?php

namespace pconfig\serializer;

/**
 * Content serializer interface
 * Interface Serializer
 * @package pconfig\serializer
 */
interface ISerializer
{

    /**
     * serialize content
     * @param array $data data
     * @return string serialized content
     */
    function serialize($data);

    /**
     * deserialize content
     * @param string $content raw content
     * @return array deserialized data
     */
    function deserialize($content);

}