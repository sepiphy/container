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

use Closure;
use ReflectionFunction;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sepiphy\PHPTools\Contracts\Container\ContainerInterface;

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

        if ($service instanceof Closure && ! in_array($id, $this->resolvedIds)) {
            $this->set($id, $instance = $service($this));

            $this->resolvedIds[] = $id;

            return $instance;
        }

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function use(string $filePath): void
    {
        if (is_file($filePath)) {
            $container = $this;

            require $filePath;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function call($callback)
    {
        if (is_string($callback) && strpos($callback, '@') > 0) {
            $callback = explode('@', $callback);
        }

        if (is_array($callback) && is_callable($callback)) {
            $class = $this->get($callback[0]);
            $method = $callback[1];
            [$class, $method] = $callback;

            $reflector = new ReflectionMethod($class, $method);

            $params = $reflector->getParameters();

            $results = [];

            foreach ($params as $param) {
                if ($param->getClass()) {
                    $results[] = $this->get($param->getClass()->getName());
                }
            }

            return $reflector->invokeArgs($this->get($class), $results);
        } else {
            $reflector = new ReflectionFunction($callback);

            $params = $reflector->getParameters();

            $results = [];

            foreach ($params as $param) {
                $results[] = $this->get($param->getClass()->getName());
            }

            return $reflector->invokeArgs($results);
        }
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
