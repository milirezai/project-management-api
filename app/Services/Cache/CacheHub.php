<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Closure;

class CacheHub
{
    protected ?int $ttl =  null;
    protected ?string $prefix = null;

    public function ttl(int $ttl): static
    {
        $this->ttl = $ttl;
        return $this;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function prefix(string $prefix): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    protected function setDefaultSettings(): void
    {
        if (is_null($this->getTtl()))
            $this->ttl(300);
        if (is_null($this->getPrefix()))
            $this->prefix('api');
    }

    public function remember(string $key, Closure $closure)
    {
        $this->setDefaultSettings();
        $key = $this->getPrefix().' : '.$key;
        return Cache::remember($key,$this->getTtl(),$closure );
    }
}
