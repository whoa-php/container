<?php

/**
 * Copyright 2015-2019 info@neomerx.com
 * Modification Copyright 2021-2022 info@whoaphp.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Whoa\Tests\Container;

use Whoa\Container\Container;
use Whoa\Container\Exceptions\NotFoundException;
use Whoa\Container\Traits\HasContainerTrait;
use PHPUnit\Framework\TestCase;

/**
 * @package Whoa\Tests\Container
 */
class ContainerTest extends TestCase
{
    /**
     * Test `get` and `has` methods.
     */
    public function testContainer(): void
    {
        $container = new Container();

        $this->assertFalse($container->has(self::class));

        $container[self::class] = $this;

        $this->assertTrue($container->has(self::class));
        $this->assertSame($this, $container->get(self::class));
    }

    /**
     * Test not found.
     */
    public function testNotFound(): void
    {
        $this->expectException(NotFoundException::class);

        (new Container())->get('non-existing');
    }

    /**
     * Test HasContainerTrait.
     */
    public function testHasContainerTrait(): void
    {
        $container = new Container();
        $class = new class {
            use HasContainerTrait {
                getContainer as public;
                setContainer as public;
                hasContainer as public;
            }
        };

        $this->assertFalse($class->hasContainer());

        $class->setContainer($container);
        $this->assertTrue($class->hasContainer());
        $this->assertEquals($container, $class->getContainer());
    }
}
