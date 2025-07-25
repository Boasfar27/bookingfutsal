@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Pilih Lapangan Favoritmu</h1>
            <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                Temukan lapangan futsal terbaik dengan fasilitas lengkap dan harga terjangkau
            </p>
        </div>
    </div>
</div>

<!-- Search & Filters -->
<div class="bg-white shadow-lg sticky top-16 z-40 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form method="GET" action="{{ route('fields.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Lapangan</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nama lapangan, lokasi..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Jenis Lapangan</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Semua Jenis</option>
                        @foreach($fieldTypes as $type)
                            <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range -->
                <div>
                    <label for="price_range" class="block text-sm font-medium text-gray-700 mb-1">Harga Per Jam</label>
                    <div class="flex space-x-2">
                        <input type="number" 
                               name="min_price" 
                               value="{{ request('min_price') }}" 
                               placeholder="Min"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="number" 
                               name="max_price" 
                               value="{{ request('max_price') }}" 
                               placeholder="Max"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select id="sort" name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $fields->count() }} dari {{ $fields->total() }} lapangan
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('fields.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Reset Filter
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Fields Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @if($fields->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($fields as $field)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Field Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($field->images && count($field->images) > 0)
                            <img src="{{ asset('storage/' . $field->images[0]) }}" 
                                 alt="{{ $field->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                    <circle cx="10" cy="12" r="3" fill="currentColor"/>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs font-medium">
                                {{ ucfirst($field->type) }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            @if($field->is_active)
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Tersedia
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                    Tutup
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Field Info -->
                    <div class="p-6">
                        <!-- Name & Location -->
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $field->name }}</h3>
                        <div class="flex items-center text-gray-600 mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm">{{ $field->location }}</span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($field->description, 100) }}
                        </p>

                        <!-- Facilities -->
                        @if($field->facilities && count($field->facilities) > 0)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(array_slice($field->facilities, 0, 3) as $facility)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                            {{ $facility }}
                                        </span>
                                    @endforeach
                                    @if(count($field->facilities) > 3)
                                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                                            +{{ count($field->facilities) - 3 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Operating Hours -->
                        <div class="flex items-center text-gray-600 mb-4">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm">{{ $field->open_time }} - {{ $field->close_time }}</span>
                        </div>

                        <!-- Price & Action -->
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                                </span>
                                <span class="text-gray-600 text-sm">/jam</span>
                            </div>
                            <a href="{{ route('fields.show', $field) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $fields->withQueryString()->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Tidak ada lapangan ditemukan</h3>
            <p class="text-gray-600 mb-8">
                Coba ubah filter pencarian atau kata kunci yang Anda gunakan
            </p>
            <a href="{{ route('fields.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                Lihat Semua Lapangan
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit form when filters change (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const selects = form.querySelectorAll('select');
        
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Optional: Auto-submit on filter change
                // form.submit();
            });
        });
    });
</script>
@endpush 