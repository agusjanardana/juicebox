<?php

namespace App\Repositories\NoteRepository;

interface NoteRepositoryInterface
{
    public function getNotesWithLimitAndOffset($offset,$limit);
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}