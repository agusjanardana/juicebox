<?php

namespace App\Repositories\UserRepository;

interface UserRepositoryInterface
{
    public function getUserByEmail($email);
    public function index();
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}