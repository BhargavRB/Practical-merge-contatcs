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

    public static function merge($request)
    {
        $master = Contact::findOrFail($request->master_id);
        $secondary = Contact::findOrFail($request->secondary_id);

        // Merge custom fields
        $masterFields = $master->custom_fields ? json_decode($master->custom_fields,true) : [];
        $secondaryFields = $secondary->custom_fields ? json_decode($secondary->custom_fields,true) : [];

        foreach ($secondaryFields as $key => $value) {
            if (!isset($masterFields[$key])) {
                $masterFields[$key] = $value;
            } else {
                $existingValue = $masterFields[$key];
                if (is_array($existingValue)) {
                    if (!in_array($value, $existingValue)) {
                        $existingValue[] = $value;
                    }
                    $masterFields[$key] = $existingValue;
                } else {
                    if ($existingValue !== $value) {
                        $masterFields[$key] = array_unique([$existingValue, $value]);
                    }
                }
            }
        } 
        
        if ($master->email !== $secondary->email) {
            $masterFields['Secondary Email'] = $secondary->email;
        }

        if ($master->phone !== $secondary->phone) {
            $masterFields['Secondary Phone'] = $secondary->phone;
        }

        $master->custom_fields = json_encode($masterFields);
        $secondary->is_merged = true;
        $secondary->merged_into = $master->id;
        $secondary->save();

        // Save master updates
        $master->save();

        return ['success' => 'Contacts merged successfully'];
    }
}