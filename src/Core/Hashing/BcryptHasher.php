<?php
namespace NINA\Core\Hashing;
use NINA\Core\Contracts\Hashing\Hasher;
class BcryptHasher implements Hasher
{
    /**
     * @throws \Exception
     */
    public function make(string $value, array $options = []): string
    {
        $hash = password_hash($value, PASSWORD_BCRYPT, $options);
        if ($hash === false) {
            throw new \Exception('Bcrypt hashing not supported.');
        }
        return $hash;
    }
    public function check(string $value, string $hashedValue, array $options = []): bool
    {
        if (strlen($hashedValue) === 0) {
            return false;
        }
        return password_verify($value, $hashedValue);
    }
}