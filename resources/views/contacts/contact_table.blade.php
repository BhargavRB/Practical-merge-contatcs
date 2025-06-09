<table class="w-full border border-gray-300 text-left mt-4">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">Name</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Phone</th>
            <th class="p-2 border">Gender</th>
            <th class="p-2 border">Custom Fields</th>
            <th class="p-2 border" style="width: 200px;">Profile Image</th> 
            <th class="p-2 border">Additional File</th>
            <th class="p-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($contacts as $contact)
        <tr class="border-t">
            <td class="p-2 border">{{ $contact->name }}</td>
            <td class="p-2 border">{{ $contact->email }}</td>
            <td class="p-2 border">{{ $contact->phone }}</td>
            <td class="p-2 border">{{ $contact->gender }}</td>
            <td class="p-2 border">
                @php
                    $customFields = json_decode($contact->custom_fields, true) ?? [];
                @endphp

                @if(count($customFields) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($customFields as $key => $value)
                            <li><strong>{{ e($key) }}:</strong> {{ e($value) }}</li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-gray-400 italic">No custom fields</span>
                @endif
            </td>
            <td class="p-2 border">
                @if($contact->profile_image_url)
                    <img src="{{ $contact->profile_image_url }}" alt="Profile Image" class="h-12 w-12 object-cover">
                @else
                    <span class="text-gray-400 italic">No Image</span>
                @endif
            </td>
            <td class="p-2 border">
                @if($contact->additional_file_url)
                <a href="{{ $contact->additional_file_url }}" target="_blank" class="text-blue-600 hover:underline">
                    {{ basename($contact->additional_file_url) }}
                </a>
            @else
                <span class="text-gray-400 italic">No File</span>
            @endif
            </td>
            <td class="p-2 border space-x-2">
                <button class="delete-contact text-red-600 font-medium" data-id="{{ $contact->id }}">Delete</button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
