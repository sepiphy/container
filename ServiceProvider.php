<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\PHPTools\Container;

use Sepiphy\PHPTools\Contracts\Container\ContainerContract;

abstract class ServiceProvider
{
    /**
     * @var ContainerContract
     */
    protected $container;

    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }

    public function getContainer(): ContainerContract
    {
        return $this->container;
    }

    public function setContainer(ContainerContract $container): self
    {
        $this->container = $container;

        return $this;
    }
}
