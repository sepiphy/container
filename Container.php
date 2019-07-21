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

use Sepiphy\PHPTools\Contracts\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Quynh Xuan Nguyen <seriquynh@gmail.com>
 */
class Container extends ContainerBuilder implements ContainerInterface
{
    /**
     * {@inheritdoc}
     */
    public function useFile(string $filePath): void
    {
        if (is_file($filePath)) {
            $container = $this;

            require $filePath;
        }
    }
}
