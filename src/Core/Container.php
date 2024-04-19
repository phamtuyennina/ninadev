<?php
namespace NINA\Core;
class Container extends \Illuminate\Container\Container
{
    private string $basePath;
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        $this->instance('path.route', $this->getRoutePath());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.views', $this->baseViewPath());
        $this->instance('path.cache', $this->getCachePath());
        $this->instance('path.config', $this->getConfigPath());
        $this->instance('path.public', $this->getPublicPath());
        $this->instance('path.thumb', $this->getThumbPath());
        $this->instance('path.storage', $this->getStoragePath());
        $this->instance('path.upload', $this->getUploadPath());
        $this->instance('path.database', $this->getDatabasePath());
        self::$instance = $this;
    }
    public static function getInstance(): Container|static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
    private function baseViewPath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'src/views';
    }
    public function getThumbPath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'thumbs';
    }
    private function getRoutePath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'src/routes';
    }
    private function getDatabasePath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'database';
    }
    public function basePath(string $path = ''): string
    {
        return !$path ? $this->basePath : $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
    public function basePathSrc(string $path = ''): string
    {
        return (!$path ? $this->basePath : $this->basePath . ($path ? DIRECTORY_SEPARATOR . $path : $path)).'\src';
    }
    private function getUploadPath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'upload';
    }
    private function getStoragePath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'storage';
    }
    private function getPublicPath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'public';
    }
    private function getCachePath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'caches';
    }
    private function getConfigPath(): string
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'config';
    }
    public function getOS(): string
    {
        return match (true) {
            stristr(PHP_OS, 'DAR') => 'macros',
            stristr(PHP_OS, 'WIN') => 'windows',
            stristr(PHP_OS, 'LINUX') => 'linux',
            default => 'unknown',
        };
    }
    public function isWindows(): bool
    {
        return "windows" === $this->getOs();
    }
    public function isMacos(): bool
    {
        return "macros" === $this->getOs();
    }
    public function isLinux(): bool
    {
        return "linux" === $this->getOs();
    }
    public function unknownOs(): bool
    {
        return "unknown" === $this->getOs();
    }
    public function publicPath($path = ''): string
    {
        return $this->joinPaths($this->getUploadPath(), $path);
    }
    public function joinPaths($basePath, $path = ''): string
    {
        return $basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}