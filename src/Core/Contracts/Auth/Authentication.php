<?php
namespace NINA\Core\Contracts\Auth;
interface Authentication
{
    public function attempt(array $options = []): bool;
    public function user(): ?\NINA\Database\Eloquent\Model;
    public function logout(): void;
    public function check(): bool;
    public function guard($guard = ""): Authentication;
}