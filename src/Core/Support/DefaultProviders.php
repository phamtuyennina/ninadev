<?php

namespace NINA\Core\Support;

class DefaultProviders
{
    protected array $providers;
    public function __construct(?array $providers = null)
    {
        $this->providers = $providers ?: [
            \NINA\Core\Hashing\HashServiceProvider::class,
            \NINA\Core\Session\SessionServiceProvider::class,
            \NINA\Core\Translator\TranslatorServiceProvider::class,
            \NINA\Core\Request\RequestServiceProvider::class,
            \NINA\Core\Config\ConfigServiceProvider::class,
            \NINA\Providers\FlashServiceProvider::class,
            \NINA\Core\Validator\ValidationServiceProvider::class,
            \NINA\Core\Auth\AuthenticationServiceProvider::class,
            \NINA\Core\View\ViewServiceProvider::class,
            \NINA\Core\Agent\AgentServiceProvider::class,
            \NINA\Core\CacheHtml\CacheHtmlServiceProvider::class,
            \NINA\Providers\BreadCrumbsServiceProvider::class,
            \NINA\Providers\SeoServiceProvider::class,
            \NINA\Providers\DatabaseServiceProvider::class,
            \NINA\Providers\FuncServiceProvider::class,
            \NINA\Core\FileSystem\FileSystemServiceProvider::class,
        ];
    }
    public function merge(array $providers): static
    {
        $this->providers = array_merge($this->providers, $providers);

        return new static($this->providers);
    }
    public function replace(array $replacements): static
    {
        $current = collect($this->providers);

        foreach ($replacements as $from => $to) {
            $key = $current->search($from);

            $current = is_int($key) ? $current->replace([$key => $to]) : $current;
        }

        return new static($current->values()->toArray());
    }
    public function except(array $providers): static
    {
        return new static(collect($this->providers)
            ->reject(fn ($p) => in_array($p, $providers))
            ->values()
            ->toArray());
    }
    public function toArray(): array
    {
        return $this->providers;
    }
}