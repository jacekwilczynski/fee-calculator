<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure;

use OutOfRangeException;
use Psr\Container\ContainerInterface;

class ServiceLocator implements ContainerInterface
{
    private array $services = [];

    public function setService(string $id, object $service): void
    {
        $this->services[$id] = $service;
    }

    public function get(string $id): object
    {
        $this->assertServiceExists($id);

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    private function assertServiceExists(string $id): void
    {
        if (!$this->has($id)) {
            throw new OutOfRangeException(sprintf(
                'Service %s not found.',
                $id,
            ));
        }
    }
}
