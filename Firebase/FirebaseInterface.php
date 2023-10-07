<?php

namespace Firebase;

interface FirebaseInterface
{
    public function setToken(string $token): void;
    public function setBaseURI(string $baseURI): void;
    public function setTimeOut(int $seconds): void;

    public function set(string $path, $data, array $options = []);
    public function push(string $path, $data, array $options = []);
    public function update(string $path, $data, array $options = []);
    public function delete(string $path, array $options = []);

    public function get(string $path, array $options = []): string;
}