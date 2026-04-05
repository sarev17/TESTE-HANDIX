<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Resources\ContactResource;
use App\Services\ContactService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse;

    protected ContactService $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $contacts = $this->service->list($request->search);

        return $this->success([
            'current_page' => $contacts->currentPage(),
            'per_page' => $contacts->perPage(),
            'total' => $contacts->total(),
            'last_page' => $contacts->lastPage(),
            'data' => ContactResource::collection($contacts->items()),
        ], 'Lista de contatos');
    }

    public function store(ContactRequest $request)
    {
        $contact = $this->service->create($request->validated());

        return $this->success(
            new ContactResource($contact),
            'Contato criado',
            201
        );
    }

    public function show(int $id)
    {
        $contact = $this->service->find($id);

        return $this->success(new ContactResource($contact));
    }

    public function update(ContactRequest $request, int $id)
    {
        $contact = $this->service->update($id, $request->validated());

        return $this->success(
            new ContactResource($contact),
            'Contato atualizado'
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return $this->success(null, 'Contato removido');
    }
}