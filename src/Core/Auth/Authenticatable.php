<?php

namespace NINA\Core\Auth;
use DB;
use Hash;
use NINA\Core\Singleton;
use Session;
use NINA\Core\Contracts\Auth\Authentication;
use NINA\Database\Eloquent\Model;
class Authenticatable implements Authentication
{
    use Singleton;
    private string $guard = "";
    private string $provider = "";
    private string $model = "";
    private ?Model $object = null;
    public function __construct()
    {
        if (!self::$instance) {
            self::setInstance($this);
        }
    }

    /**
     * @throws \Exception
     */
    public function attempt(array $options = []): bool
    {
        $model = new $this->model;
        $columnPassword = $model->password();
        $table = $model->table();
        $paramPassword = $options[$columnPassword];
        unset($options[$columnPassword]);
        $object = DB::table($table)->where($options)->first();
        if (!$object || $object && !Hash::check($paramPassword, $object->password)) {
            return false;
        }
        return $this->setUserAuth(
            $this->model::where($options)->firstOrFail()
        );
    }
    public function user(): ?Model
    {
        if (!is_null($this->getObject())) {
            return $this->getObject();
        }
        $guardDriver = $this->getConfigDriverFromGuard(
            $this->getCurrentGuard()
        );
        switch ($guardDriver) {
            case 'session':
                return Session::get($this->guard);
            default:
                throw new \Exception('Unknown authentication');
        }
    }
    public function trueFormatKey(string $key): string
    {
        return base64_decode(strtr($key, '-_', '+/'));
    }
    public function logout(): void
    {
        $guardDriver = $this->getConfigDriverFromGuard(
            $this->getCurrentGuard()
        );
        switch ($guardDriver) {
            case 'session':
                Session::unset($this->guard);
                break;
            default:
                throw new \Exception("Unknown authentication");
        }
    }
    public function check(): bool
    {
        if (!is_null($this->user()) && !empty($this->user())) {
            return true;
        }
        return false;
    }
    private function setUserAuth(Model $user): bool
    {

        $this->setObject($user);
        $guardDriver = $this->getConfigDriverFromGuard(
            $this->getCurrentGuard()
        );
        switch ($guardDriver) {
            case 'session':
                Session::set($this->guard, $this->getObject());
                break;
            default:
                throw new \Exception("Unknown authentication");
        }
        return true;
    }
    public function guard($guard = ""): Authenticatable
    {
        if (empty($guard)) {
            $guard = $this->getDefaultGuard();
        }

        $this->setGuard($guard);
        $guard = $this->getCurrentGuard();
        $this->setProvider(
            $this->getConfigProviderFromGuard($guard)
        );
        $provider = $this->getProvider();
        $this->setModel(
            $this->getConfigModelFromProvider($provider)
        );
        return $this;
    }
    protected function getConfigModelFromProvider(string $provider): string
    {
        return config("auth.providers.{$provider}.model");
    }
    protected function getConfigProviderFromGuard(string $guard): string
    {
        return config("auth.guards.{$guard}.provider");
    }
    protected function getConfigDriverFromGuard(string $guard): string
    {
        return config("auth.guards.{$guard}.driver");
    }
    protected function setModel(string $model): void
    {
        $this->model = $model;
    }
    protected function getProvider(): string
    {
        return $this->provider;
    }
    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }
    public function setGuard(string $guard): void
    {
        $this->guard = $guard;
    }
    private function getDefaultGuard(): string
    {
        return config('auth.defaults.guard');
    }
    public function getCurrentGuard(): string
    {
        return $this->guard;
    }
    protected function setObject(Model $object): void
    {
        $this->object = $object;
    }
    protected function getObject(): ?Model
    {
        return $this->object;
    }
}