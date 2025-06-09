<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContactService
{
    public static function store($request)
    {
        $customFieldsInput = $request->input('custom_fields', []);
        $customFields = [];

        foreach ($customFieldsInput as $field) {
            if (!empty($field['key'])) {
                $customFields[$field['key']] = $field['value'] ?? null;
            }
        }

        // Now store in DB as JSON
        $customFields= json_encode($customFields);

        $contact = Contact::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'custom_fields' => $customFields
        ]);

        if ($request->hasFile('profile_image')) {
            $contact->addMedia($request->file('profile_image'))->toMediaCollection('profile_images');
        }

        if ($request->hasFile('additional_file')) {
            $contact->addMedia($request->file('additional_file'))->toMediaCollection('additional_files');
        }

        return ['success' => 'Contact added successfully'];
    }
}