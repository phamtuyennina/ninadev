<?php
namespace NINA\Core\Session;
use Illuminate\Support\Arr;

session_start();
class Session
{

    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }
    public function put($key, $value = null): void
    {
        $_SESSION[$key] = $this->has($key)?$_SESSION[$key]:[];
        if (!is_array($key)) {
            $keyPut = [$key => $value];
        }
        foreach ($keyPut as $arrayKey => $arrayValue) {
            Arr::set($_SESSION[$key], $arrayKey, $arrayValue);
        }
    }
    public function has($key): bool
    {
        if (isset($_SESSION[$key]) && is_array($_SESSION[$key])) {
            return !empty($_SESSION[$key]);
        } else {
            return false;
        }
    }
    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }
    public function get(string $key)
    {
        return $this->isset($key) ? $_SESSION[$key] : null;
    }
    public function storage(): array
    {
        return $_SESSION;
    }
}