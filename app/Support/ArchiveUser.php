<?php

namespace App\Support;

use App\Contracts\ListsMailboxes;
use Illuminate\Contracts\Routing\UrlRoutable;

class ArchiveUser implements UrlRoutable
{
    public function __construct(
        public string $username = '',
    ) {

    }

    public function __serialize(): array
    {
        return ['username' => $this->username];
    }

    public function __unserialize(array $data): void
    {
        $this->username = $data['username'];
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public static function from_string(string $username): self
    {
        return new self($username);
    }

    public function getRouteKey()
    {
        return $this->{$this->getRouteKeyName()};
    }

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return app(ListsMailboxes::class)
            ->listMailboxesAvailable()
            ->mailboxes
            ->first(fn ($x) => $x->email->username === $value)
            ?->email;
    }

    public function resolveChildRouteBinding($childType, $value, $field)
    {
        abort(500, 'unimplemented');
    }
}
