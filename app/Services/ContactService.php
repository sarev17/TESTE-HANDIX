<?php

namespace App\Services;

use App\Models\Contact;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function list($search = null)
    {
        try {
            $query = Contact::query();

            if ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            }

            return $query->latest()->paginate(10);

        } catch (\Throwable $e) {

            Log::error('Erro ao listar contatos', [
                'search' => $search,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw new ServiceException('Erro ao listar contatos');
        }
    }

    public function create(array $data)
    {
        try {
            return Contact::create($data);

        } catch (\Throwable $e) {

            Log::error('Erro ao criar contato', [
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException('Erro ao criar contato');
        }
    }

    public function find(int $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {

            Log::warning('Contato não encontrado', [
                'contact_id' => $id
            ]);

            throw new ServiceException('Contato não encontrado', [], 404);
        }

        return $contact;
    }

    public function update(int $id, array $data)
    {
        try {
            $contact = $this->find($id);
            $contact->update($data);

            return $contact;

        } catch (ServiceException $e) {
            throw $e;

        } catch (\Throwable $e) {

            Log::error('Erro ao atualizar contato', [
                'contact_id' => $id,
                'data' => $data,
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException('Erro ao atualizar contato');
        }
    }

    public function delete(int $id)
    {
        try {
            $contact = $this->find($id);
            $contact->delete();

            return true;

        } catch (ServiceException $e) {
            throw $e;

        } catch (\Throwable $e) {

            Log::error('Erro ao remover contato', [
                'contact_id' => $id,
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException('Erro ao remover contato');
        }
    }
}