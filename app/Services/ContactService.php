<?php

namespace App\Services;

use App\Models\Contact;
use App\Exceptions\ServiceException;

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

        } catch (\Exception $e) {
            throw new ServiceException('Erro ao listar contatos');
        }
    }

    public function create(array $data)
    {
        try {
            return Contact::create($data);

        } catch (\Exception $e) {
            throw new ServiceException('Erro ao criar contato');
        }
    }

    public function find(int $id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
            throw new ServiceException('Erro ao remover contato');
        }
    }
}