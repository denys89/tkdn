@extends('layouts.app')

@section('title', 'Harga Pokok Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Harga Pokok Pembelian</h1>
            <p class="text-gray-600 dark:text-gray-400">Kelola data HPP (Harga Pokok Pembelian) untuk berbagai jenis pekerjaan</p>
        </div>
        <div class="mt-4 sm:mt-0">
            @can('manage-hpp')
            <a href="{{ route('hpp.create') }}" class="btn btn-primary flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah HPP
            </a>
            @endcan
        </div>
    </div>

    <!-- Notification Messages -->
    @if(session('success'))
        <div class="mb-6">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl relative flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- HPP Table -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daftar HPP</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Click row to view details
                </p>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode HPP</th>
                            <th>Project</th>
                            <th>Jenis Project</th>
                            <th>Perusahaan</th>
                            <th>Sub Total HPP</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hpps as $hpp)
                        <tr class="cursor-pointer hover:bg-blue-100 dark:hover:bg-gray-600 transition-colors border-b border-gray-100 dark:border-gray-700" data-detail-url="{{ route('hpp.show', $hpp->id) }}" onclick="goToDetail(this, event)">
                            <td>{{ $loop->iteration + ($hpps->firstItem() - 1) }}</td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $hpp->code }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $hpp->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $hpp->project->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ \App\Helpers\StringHelper::safeLimit($hpp->project->description ?? '', 20) }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $hpp->project && $hpp->project->project_type === 'tkdn_jasa' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' }}">
                                    @if($hpp->project)
                                        @if($hpp->project->project_type === 'tkdn_jasa')
                                            TKDN Jasa
                                        @elseif($hpp->project->project_type === 'tkdn_barang_jasa')
                                            TKDN Barang & Jasa
                                        @else
                                            {{ $hpp->project->project_type }}
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </td>
                            <td>
                                <span class="text-sm text-gray-700 dark:text-gray-200">{{ $hpp->project->company ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Rp {{ number_format($hpp->sub_total_hpp, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">Rp {{ number_format($hpp->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'draft' => 'badge-warning',
                                        'submitted' => 'badge-primary',
                                        'approved' => 'badge-success',
                                        'rejected' => 'badge-danger',
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'submitted' => 'Diajukan',
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$hpp->status] }}">
                                    {{ $statusLabels[$hpp->status] }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center space-x-2" onclick="event.stopPropagation()">
                                    @if($hpp->status === 'draft' && auth()->user()->can('manage-hpp'))
                                        <a href="{{ route('hpp.edit', $hpp->id) }}" class="btn btn-outline p-2 text-yellow-600 hover:text-yellow-700 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('hpp.destroy', $hpp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus HPP ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if($hpp->status === 'submitted' && auth()->user()->can('manage-hpp'))
                                        <form action="{{ route('hpp.approve', $hpp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui HPP ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline p-2 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300" title="Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                        
                                        <!-- <form action="{{ route('hpp.reject', $hpp->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menolak HPP ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300" title="Reject">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form> -->
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada HPP ditemukan</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Mulai dengan menambah HPP baru</p>
                                    @can('manage-hpp')
                                    <a href="{{ route('hpp.create') }}" class="btn btn-primary">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Tambah HPP
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($hpps->hasPages())
        {{ $hpps->links('components.pagination') }}
    @endif
</div>

<script>
function goToDetail(element, event) {
    // Check if the click is on a button or link
    if (event.target.closest('button') || event.target.closest('a')) {
        return;
    }
    
    const detailUrl = element.getAttribute('data-detail-url');
    if (detailUrl) {
        window.location.href = detailUrl;
    }
}
</script>
@endsection
