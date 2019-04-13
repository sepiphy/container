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
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Container extends ContainerBuilder implements ContainerContract
{
    protected $callbacks = [];
    protected $sharing = [];
    protected $instances = [];

    /**
     * {@inheritdoc}
     */
    public function bind(string $id, Closure $callback): void
    {
        $this->callbacks[$id] = $callback;
        $this->sharing[$id] = false;
    }

    /**
     * {@inheritdoc}
     */
    public function once(string $id, Closure $callback): void
    {
        $this->callbacks[$id] = $callback;
        $this->sharing[$id] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function make($id)
    {
        if ($this->sharing[$id] && array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (isset($this->callbacks[$id])) {
            return $this->instances[$id] = $this->callbacks[$id]($this);
        }

        return null;
    }
}
