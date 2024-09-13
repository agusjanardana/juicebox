<?php

namespace App\Repositories\UserRepository;

use App\Models\User;

class UserRepositoryImpl implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index() {
    return User::all();
    }
    public function getById($id){
    return User::find($id);
    }
    public function store(array $data){
    return User::create($data);
    }
    public function update(array $data,$id) {
    return User::find($id)->update($data);
    }
    public function delete($id) {
    return User::destroy($id);
    }

    public function getUserByEmail($email) {
        return User::where('email', $email)->first();
    }
}