<?php

namespace App\Repositories\NoteRepository;

use App\Models\Note;

class NoteRepositoryImpl implements NoteRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getNotesWithLimitAndOffset($offset, $limit) {
        return Note::orderBy('id')
        ->skip($offset)
        ->take($limit)
        ->get();
    }

    public function getById($id){
        return Note::find($id);
    }

    public function store(array $data){
        return Note::create($data);
    }

    public function update(array $data,$id) {
        return Note::find($id)->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'updated_at' => $data['updated_at']
        ]);
    }

    public function delete($id) {
        return Note::destroy($id);
    }
}