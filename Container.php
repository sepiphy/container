<?php declare(strict_types=1);

/*
 * This file is part of the sepiphy/phptools package.
 *
 * (c) Quynh Xuan Nguyen <seriquynh@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sepiphy\Container;

use Closure;
use ReflectionFunction;
use ReflectionMethod;
use Sepiphy\Contracts\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class Container extends ContainerBuilder implements ContainerInterface
{
    /**
     * The services have been resolved by calling callback resolutions.
     *
     * @var array
     */
    protected $resolvedIds = [];

    /**
     * {@inheritdoc}
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $service = parent::get($id, $invalidBehavior);

        if (in_array($id, $this->resolvedIds)) {
            return $service;
        }

        if ($service instanceof Closure) {
            $this->set($id, $instance = $service($this));

            $this->resolvedIds[] = $id;

            return $instance;
        }

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $providers): void
    {
        $instances = [];

        foreach ($providers as $provider) {
            $instances[$provider] = $instance = new $provider($this);
            $instance->register();
        }

        foreach ($instances as $instance) {
            $instance->boot();
        }
    }
}
