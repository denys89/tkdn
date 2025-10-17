@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Edit Service</h1>
            <p class="text-gray-600 dark:text-gray-400">Edit formulir TKDN jasa</p>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('service.update', $service) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Informasi Umum -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Umum</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_id" class="form-label">Proyek <span class="text-red-500">*</span></label>
                        <select name="project_id" id="project_id" class="form-select @error('project_id') border-red-500 @enderror" required>
                            <option value="">Pilih Proyek</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $service->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Kategori Form TKDN</label>
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->getFormCategoryLabel() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Ditentukan otomatis berdasarkan form yang tersedia di HPP</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Jenis Service</label>
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->getServiceTypeLabel() }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Ditentukan otomatis berdasarkan jenis project</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="service_name" class="form-label">Nama Service <span class="text-red-500">*</span></label>
                        <input type="text" name="service_name" id="service_name" class="form-input @error('service_name') border-red-500 @enderror" value="{{ old('service_name', $service->service_name) }}" required>
                        @error('service_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="provider_name" class="form-label">Penyedia Barang/Jasa</label>
                        <input type="text" name="provider_name" id="provider_name" class="form-input @error('provider_name') border-red-500 @enderror" value="{{ old('provider_name', $service->provider_name) }}">
                        @error('provider_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="user_name" class="form-label">Pengguna Barang/Jasa</label>
                        <input type="text" name="user_name" id="user_name" class="form-input @error('user_name') border-red-500 @enderror" value="{{ old('user_name', $service->user_name) }}">
                        @error('user_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_number" class="form-label">No. Dokumen Service</label>
                        <input type="text" name="document_number" id="document_number" class="form-input @error('document_number') border-red-500 @enderror" value="{{ old('document_number', $service->document_number) }}">
                        @error('document_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="provider_address" class="form-label">Alamat Penyedia</label>
                        <textarea name="provider_address" id="provider_address" rows="3" class="form-textarea @error('provider_address') border-red-500 @enderror">{{ old('provider_address', $service->provider_address) }}</textarea>
                        @error('provider_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Item Service -->
        <div class="card">
            <div class="card-header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Item Service</h3>
                    <button type="button" onclick="addItem()" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Item
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="items-container">
                    @foreach($service->itemsOrdered as $index => $item)
                    <div class="item-row border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white">Item {{ $index + 1 }}</h4>
                            <button type="button" onclick="removeItem(this)" class="btn btn-outline p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Uraian <span class="text-red-500">*</span></label>
                                <input type="text" name="items[{{ $index }}][description]" class="form-input" value="{{ $item->description }}" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Kualifikasi</label>
                                <input type="text" name="items[{{ $index }}][qualification]" class="form-input" value="{{ $item->qualification }}">
                            </div>
                            
                            <div>
                                <label class="form-label">Kewarganegaraan <span class="text-red-500">*</span></label>
                                <select name="items[{{ $index }}][nationality]" class="form-select" required>
                                    <option value="">Pilih Kewarganegaraan</option>
                                    <option value="WNI" {{ $item->nationality == 'WNI' ? 'selected' : '' }}>WNI</option>
                                    <option value="WNA" {{ $item->nationality == 'WNA' ? 'selected' : '' }}>WNA</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="form-label">TKDN (%) <span class="text-red-500">*</span></label>
                                <input type="number" name="items[{{ $index }}][tkdn_percentage]" class="form-input" min="0" max="100" step="0.01" value="{{ $item->tkdn_percentage }}" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Jumlah <span class="text-red-500">*</span></label>
                                <input type="number" name="items[{{ $index }}][quantity]" class="form-input" min="1" value="{{ $item->quantity }}" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Durasi <span class="text-red-500">*</span></label>
                                <input type="number" name="items[{{ $index }}][duration]" class="form-input" min="0" step="0.01" value="{{ $item->duration }}" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Satuan Durasi <span class="text-red-500">*</span></label>
                                <input type="text" name="items[{{ $index }}][duration_unit]" class="form-input" maxlength="10" value="{{ $item->duration_unit }}" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Upah (Rupiah) <span class="text-red-500">*</span></label>
                                <input type="number" name="items[{{ $index }}][wage]" class="form-input" min="0" value="{{ $item->wage }}" required>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('service.show', $service) }}" class="btn btn-secondary">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                Update Service
            </button>
        </div>
    </form>
</div>

<!-- Template for item -->
<template id="item-template">
    <div class="item-row border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-medium text-gray-900 dark:text-white">Item <span class="item-number"></span></h4>
            <button type="button" onclick="removeItem(this)" class="btn btn-outline p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="form-label">Uraian <span class="text-red-500">*</span></label>
                <input type="text" name="items[INDEX][description]" class="form-input" required>
            </div>
            
            <div>
                <label class="form-label">Kualifikasi</label>
                <input type="text" name="items[INDEX][qualification]" class="form-input">
            </div>
            
            <div>
                <label class="form-label">Kewarganegaraan <span class="text-red-500">*</span></label>
                <select name="items[INDEX][nationality]" class="form-select" required>
                    <option value="">Pilih Kewarganegaraan</option>
                    <option value="WNI">WNI</option>
                    <option value="WNA">WNA</option>
                </select>
            </div>
            
            <div>
                <label class="form-label">TKDN (%) <span class="text-red-500">*</span></label>
                <input type="number" name="items[INDEX][tkdn_percentage]" class="form-input" min="0" max="100" step="0.01" required>
            </div>
            
            <div>
                <label class="form-label">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="items[INDEX][quantity]" class="form-input" min="1" required>
            </div>
            
            <div>
                <label class="form-label">Durasi <span class="text-red-500">*</span></label>
                <input type="number" name="items[INDEX][duration]" class="form-input" min="0" step="0.01" required>
            </div>
            
            <div>
                <label class="form-label">Satuan Durasi <span class="text-red-500">*</span></label>
                <input type="text" name="items[INDEX][duration_unit]" class="form-input" maxlength="10" required>
            </div>
            
            <div>
                <label class="form-label">Upah (Rupiah) <span class="text-red-500">*</span></label>
                <input type="number" name="items[INDEX][wage]" class="form-input" min="0" required>
            </div>
        </div>
    </div>
</template>

<script>
let itemIndex = parseInt('{{ $service->itemsOrdered ? count($service->itemsOrdered) : 0 }}') || 0;

function addItem() {
    const container = document.getElementById('items-container');
    const template = document.getElementById('item-template');
    const clone = template.content.cloneNode(true);
    
    // Update index
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.name = input.name.replace('INDEX', itemIndex);
    });
    
    // Update item number
    const itemNumber = clone.querySelector('.item-number');
    itemNumber.textContent = itemIndex + 1;
    
    container.appendChild(clone);
    itemIndex++;
}

function removeItem(button) {
    const itemRow = button.closest('.item-row');
    itemRow.remove();
}
</script>
@endsection 