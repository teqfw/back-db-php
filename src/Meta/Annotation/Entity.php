<?php
/**
 * Authors: Alex Gusev <alex@flancer64.com>
 * Since: 2019
 */

namespace TeqFw\Lib\Db\Meta\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class Entity
{
    /** @var string */
    public $name;
}