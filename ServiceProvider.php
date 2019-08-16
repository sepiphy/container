<?php declare(strict_types=1);

/*
 * This file is part of the Sepiphy package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\Container;

use Sepiphy\Contracts\Container\ContainerInterface;
use Sepiphy\Contracts\Container\ServiceProviderInterface;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
abstract class ServiceProvider implements ServiceProviderInterface
{
    /**
     * The ContainerInterface implementation.
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Create a new ServiceProvider instance.
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        //
    }

    /**
     * Get the ContainerInterface implementation.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Set the ContainerInterface implementation.
     *
     * @param ContainerInterface $container
     * @return $this
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        return $this;
    }
}
