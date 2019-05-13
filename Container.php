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
use Sepiphy\PHPTools\Contracts\Container\ContainerContract;
use Sepiphy\PHPTools\Container\Exceptions\NotFoundException;

class Container implements ContainerContract
{
    /**
     * @var array
     */
    protected $bindings = [];

    /**
     * @var array
     */
    protected $instances = [];

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return array_key_exists($id, $this->instances) ||
            array_key_exists($id, $this->bindings);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->make($id);
        }

        throw new NotFoundException(
            sprintf('"%s" was not registered in the container.', $id)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function bind(string $id, Closure $callback)
    {
        $this->bindings[$id] = [
            'shared' => false,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function once(string $id, Closure $callback)
    {
        $this->bindings[$id] = [
            'shared' => true,
            'callback' => $callback,
        ];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $id, $value)
    {
        $this->instances[$id] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function make(string $id)
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (array_key_exists($id, $this->bindings)) {
            ['shared' => $shared, 'callback' => $callback] = $this->bindings[$id];

            if ($shared) {
                return $this->instances[$id] = $callback($this);
            }

            return $callback($this);
        }

        return null;
    }
}
