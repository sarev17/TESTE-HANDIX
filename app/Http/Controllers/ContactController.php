<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse;

    protected $service;

    public function __construct(ContactService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $contacts = $this->service->list($request->search);

        return $this->success($contacts, 'Lista de contatos');
    }

    public function store(ContactRequest $request)
    {
        $contact = $this->service->create($request->validated());

        return $this->success($contact, 'Contato criado', 201);
    }

    public function show($id)
    {
        $contact = $this->service->find($id);

        return $this->success($contact);
    }

    public function update(ContactRequest $request, $id)
    {
        $contact = $this->service->update($id, $request->validated());

        return $this->success($contact, 'Contato atualizado');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return $this->success([], 'Contato removido');
    }
}