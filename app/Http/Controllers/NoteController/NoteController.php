<?php

namespace App\Http\Controllers\NoteController;

use App\Http\Controllers\Controller;
use App\Repositories\NoteRepository\NoteRepositoryInterface;
use App\Http\Controllers\BaseResponse;
use App\Http\Resources\NoteResource;
use App\Http\Requests\StoreNoteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

// use Yajra datatable
use Yajra\DataTables\Facades\DataTables;


use Illuminate\Http\Request;

class NoteController extends Controller
{
    private NoteRepositoryInterface $noteRepository;
    public function __construct(NoteRepositoryInterface $noteRepositoryInterface)
    {
        $this->noteRepository = $noteRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     * show all notes
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 10); // Default limit 10 jika tidak ada parameter
            $page = $request->query('page', default: 1); // default 1, karena pagination berarti pakai "page" saja untuk mempermudah alih-alih menggunakan offset.

            $offset = ($page - 1) * $limit;

            $notes = $this->noteRepository->getNotesWithLimitAndOffset($offset, $limit);

            $dataTable = DataTables::of($notes)
                ->toJson(); //

            return BaseResponse::sendSuccessResponse(   $dataTable->original, 'success', 200);
        } catch (\Exception $e) {
            return BaseResponse::throw($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            DB::beginTransaction();
            $dataReq =[
                'title' => $request->title,
                'content' => $request->contents,
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'updated_at' => now()
            ];
            $note = $this->noteRepository->store($dataReq);
            return BaseResponse::sendSuccessResponse(new NoteResource($note), 'Note created successfully', 201);
        } catch (\Exception $e) {
            dd($e);
            return BaseResponse::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            if (!$id) {
                return BaseResponse::sendError('Note id is required', null, 400);
            }

            $note = $this->noteRepository->getById($id);
            if (!$note) {
                return BaseResponse::sendError('Note not found', null, 404);
            }
            return BaseResponse::sendSuccessResponse(new NoteResource($note), 'success', 200);
        } catch (\Exception $e) {
            return BaseResponse::throw($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreNoteRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $dataReq = [
                'title' => $request->title,
                'content' => $request->contents,
                'updated_at' => now()
            ];
            $note = $this->noteRepository->update($dataReq, $id);
            return BaseResponse::sendSuccessResponse($dataReq, 'Note updated successfully', 200);
        } catch (\Exception $e) {
            return BaseResponse::rollback($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if (!$id) {
                return BaseResponse::sendError('Note id is required', null, 400);
            }

            $note = $this->noteRepository->getById($id);
            if (!$note) {
                return BaseResponse::sendError('Note not found', null, 404);
            }

            $this->noteRepository->delete($id);
            return BaseResponse::sendSuccessResponse(null, 'Note deleted successfully', 200);
        } catch (\Exception $e) {
            return BaseResponse::rollback($e);
        }
    }
}