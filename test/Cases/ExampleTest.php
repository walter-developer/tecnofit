<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Cases;

use Hyperf\Testing\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    public function testExample(): void
    {
        $this->realTestName = 'runExampleInCoroutine';
        $this->runTestsInCoroutine();
    }

    public function runExampleInCoroutine(): void
    {
        $this->get('/')->assertOk()->assertSee('Hyperf');
    }
}
