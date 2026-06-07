@extends('admin.layouts.app')

@section('title', 'Import Users')

@section('header', 'Import Users')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6 lg:p-8">
            <!-- Import Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Import Instructions</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ol class="list-decimal list-inside space-y-1">
                                <li>Download the sample CSV template to understand the required format</li>
                                <li>Fill in your user data following the template structure</li>
                                <li>Upload the CSV file to import users</li>
                                <li>Review the import summary and confirm the import</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Template -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Download Template</h3>
                <p class="text-sm text-gray-600 mb-4">Download the CSV template to ensure your data is formatted correctly.</p>
                <a href="{{ route('admin.users.download-template') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-download mr-2"></i>
                    Download CSV Template
                </a>
            </div>

            <!-- Upload Form -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Upload CSV File</h3>
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CSV File *</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-file-csv text-3xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a CSV file</span>
                                        <input id="file-upload" name="csv_file" type="file" accept=".csv" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">CSV only, up to 10MB</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="send_welcome_email" class="ml-2 block text-sm text-gray-900">
                            Send welcome email to imported users
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="skip_existing" id="skip_existing" value="1"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="skip_existing" class="ml-2 block text-sm text-gray-900">
                            Skip users with existing email addresses
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-upload mr-2"></i>
                            Import Users
                        </button>
                    </div>
                </form>
            </div>

            <!-- Import Preview (shown after validation) -->
            @if(session('import_preview'))
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Import Preview</h3>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">
                            Found {{ session('import_preview.total') }} users in the CSV file.
                            {{ session('import_preview.valid') }} are valid and ready to import.
                            @if(session('import_preview.invalid') > 0)
                                {{ session('import_preview.invalid') }} have errors and will be skipped.
                            @endif
                        </p>
                    </div>
                    
                    @if(session('import_preview.errors'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-medium text-red-800 mb-2">Validation Errors</h4>
                            <div class="space-y-2">
                                @foreach(session('import_preview.errors') as $error)
                                    <div class="text-sm text-red-700">
                                        Row {{ $error['row'] }}: {{ $error['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.users.import.confirm') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="import_data" value="{{ json_encode(session('import_preview.data')) }}">
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(session('import_preview.data') as $index => $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user['first_name'] }} {{ $user['last_name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user['email'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user['role'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user['status'] == 'valid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $user['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($user['error'])
                                                    {{ $user['error'] }}
                                                @else
                                                    Ready to import
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.users.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md text-sm font-medium">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-check mr-2"></i>
                                Confirm Import
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // File upload preview
    const fileInput = document.getElementById('file-upload');
    const dropZone = fileInput.closest('.border-dashed');
    
    fileInput.addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            dropZone.querySelector('span').textContent = fileName;
        }
    });
    
    // Drag and drop functionality
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-500');
    });
    
    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500');
    });
    
    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-500');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            const fileName = files[0].name;
            dropZone.querySelector('span').textContent = fileName;
        }
    });
</script>
@endpush
@endsection
