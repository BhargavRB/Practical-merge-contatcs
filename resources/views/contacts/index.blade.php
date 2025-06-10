@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow space-y-6">
    <h2 class="text-2xl font-semibold text-gray-700">Contacts</h2>

    <!-- Add Contact Form -->
    <form id="addContactForm" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter name" class="border rounded-lg p-2.5 w-full text-gray-900 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email" class="border rounded-lg p-2.5 w-full text-gray-900 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" id="phone" name="phone" placeholder="Enter phone" class="border rounded-lg p-2.5 w-full text-gray-900 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="Male" class="form-radio h-4 w-4 text-blue-600">
                        <span class="ml-2">Male</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="gender" value="Female" class="form-radio h-4 w-4 text-blue-600">
                        <span class="ml-2">Female</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div class="space-y-2">
                <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                <input type="file" id="profile_image" name="profile_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            <div class="space-y-2">
                <label for="additional_file" class="block text-sm font-medium text-gray-700">Additional File</label>
                <input type="file" id="additional_file" name="additional_file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        <div id="customFields" class="space-y-2"></div>

        <button type="button" id="addCustomField" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Custom Field
        </button>

        <div class="mt-6">
            <x-primary-button class="ms-4">
                Add Contact
            </x-primary-button>
        </div>
    </form>

    <hr class="my-6 border-gray-200">

    <!-- Filter -->
    <form id="filterForm" class="flex flex-wrap gap-4 items-center">
        <input type="text" name="search" placeholder="Search" class="border rounded-lg p-2.5 text-gray-900 focus:ring-blue-500 focus:border-blue-500">
    
        <x-primary-button class="ms-4">
            Filter
        </x-primary-button>
    </form>

    <hr class="my-6 border-gray-200">

    <!-- Contact Table -->
    <div id="contactsTable"></div>

    <div id="mergeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 space-y-4 max-w-md mx-auto shadow-xl relative p-4 w-auto max-w-2xl max-h-full" style="width:50%;">
            <h2 class="text-lg font-semibold">Select Master Contact</h2>
            <form id="mergeForm">
                @csrf
                <input type="hidden" name="secondary_id" id="secondary_id">
    
                <label class="block mb-2">Select master contact:</label>
                <select name="master_id" id="master_id" class="border p-2 w-full rounded" required>
                </select>
    
                <div class="flex justify-end space-x-2 pt-4 gap-4">
                    <button type="button" id="cancelMerge" class="bg-red-600 text-white px-4 py-2 rounded-md">Cancel</button>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md">Confirm Merge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(function(){

    function loadContacts() {
        $.get("{{ route('contacts.filter') }}", function(data){
            $('#contactsTable').html(data);
        });
    }

    loadContacts();

    $('#addContactForm').on('submit', function(e){
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            url: "{{ route('contacts.store') }}",
            type: "POST",
            data: formData,
            contentType:false,
            processData:false,
            success: function(res){
                toastr.success(res.success);
                loadContacts();
                $('#addContactForm')[0].reset();
                $('#customFields').empty();
            },
            error: function(err){
                toastr.error(err.responseJSON.message);
            }
        });
    });

    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        $.get("{{ route('contacts.filter') }}", $(this).serialize(), function(data){
            $('#contactsTable').html(data);
        });
    });

    $(document).on('click', '.delete-contact', function(){
        if(confirm('Delete this contact?')){
            $.ajax({
                url: "/contacts/delete/"+$(this).data('id'),
                type: 'DELETE',
                data: {_token: "{{ csrf_token() }}"},
                success: function(res){
                    toastr.success(res.success);
                    loadContacts();
                },
                error: function(err){
                toastr.error(err.responseJSON.message);
                }
            });
        }
    });

    $('#addCustomField').click(function(){
        const timestamp = Date.now();
        $('#customFields').append(`
            <div class="flex space-x-2 mt-2">
                <input type="text" name="custom_fields[${timestamp}][key]" placeholder="Field Name (key)" class="border rounded-lg p-2.5 text-gray-900 focus:ring-blue-500 focus:border-blue-500" required>
                <input type="text" name="custom_fields[${timestamp}][value]" placeholder="Field Value" class="border rounded-lg p-2.5 text-gray-900 focus:ring-blue-500 focus:border-blue-500" required>
                <button type="button" class="removeCustomField text-red-500 font-semibold ml-2 p-2">Remove</button>
            </div>
        `);
    });

    $(document).on('click', '.merge-contact', function(){
        let secondaryId = $(this).data('id');
        $('#secondary_id').val(secondaryId);
        $('#master_id').empty().append('<option value="">Select Master</option>');

        $.get("/contacts/master-list/" + secondaryId, function(contacts){
            $.each(contacts, function(index, contact){
                $('#master_id').append(
                    `<option value="${contact.id}">${contact.name} (${contact.email})</option>`
                );
            });
            $('#mergeModal').removeClass('hidden').addClass('flex');
        });
    });

    $(document).on('click', '.removeCustomField', function(){
        $(this).parent().remove();
    });

    $(document).on('click', '.merge-contact', function(){
        let contactId = $(this).data('id');
        $('#secondary_id').val(contactId);
        $('#mergeModal').removeClass('hidden flex').addClass('flex');
    });

    $('#cancelMerge').click(function(){
        $('#mergeModal').addClass('hidden').removeClass('flex');
    });

    $('#mergeForm').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: "{{ route('contacts.merge') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            toastr.success(res.success);
            $('#mergeModal').addClass('hidden').removeClass('flex');
            loadContacts();
        },
        error: function(xhr){
            toastr.error(xhr.responseJSON.message);
        }
    });
});

});
</script>
@endsection
