<?php

namespace App\Services;

use App\Models\Contact;

class ContactService
{
    public function list($search = null)
    {
        $query = Contact::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return Contact::create($data);
    }

    public function find(int $id)
    {
        return Contact::findOrFail($id);
    }

    public function update(int $id, array $data)
    {
        $contact = $this->find($id);
        $contact->update($data);

        return $contact;
    }

    public function delete(int $id)
    {
        $contact = $this->find($id);
        $contact->delete();

        return true;
    }
}