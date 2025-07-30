@extends('layouts.app')

@section('title', $field->name . ' - Detail Lapangan')

@section('content')
<!-- Field Detail Header -->
<section class="bg-gradient-to-br from-blue-600 to-blue-700 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <nav class="text-blue-200 text-sm mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span class="mx-2">›</span>
                <a href="{{ route('fields.index') }}" class="hover:text-white transition-colors">Lapangan</a>
                <span class="mx-2">›</span>
                <span class="text-white">{{ $field->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $field->name }}</h1>
            <div class="flex items-center justify-center space-x-6 text-blue-100">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $field->location }}
                </div>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-yellow-400 font-semibold">4.8</span>
                    <span class="ml-1">(126 review)</span>
                </div>
                <div class="flex items-center">
                    <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm font-medium">
                        {{ ucfirst($field->type) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Field Content -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Field Images -->
                <div class="mb-8">
                    <div class="aspect-video bg-gradient-to-br from-green-400 to-green-500 rounded-2xl shadow-lg mb-4 relative overflow-hidden">
                        @if($field->images && count($field->images) > 0)
                            <img src="{{ $field->images[0] }}" alt="{{ $field->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-white">
                                    <svg class="w-24 h-24 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 2L3 7v11h14V7l-7-5zM18 18H2V7.414L10 .586 18 7.414V18z"/>
                                        <circle cx="10" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1"/>
                                    </svg>
                                    <p class="text-xl font-bold">{{ $field->name }}</p>
                                    <p class="text-green-100">Lapangan Futsal Premium</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4">
                            <div class="bg-yellow-500 text-white px-4 py-2 rounded-xl font-bold shadow-lg">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam
                            </div>
                        </div>
                    </div>

                    <!-- Thumbnail Images -->
                    @if($field->images && count($field->images) > 1)
                        <div class="grid grid-cols-4 gap-2">
                            @foreach(array_slice($field->images, 1, 4) as $image)
                                <div class="aspect-video bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ $image }}" alt="{{ $field->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Field Description -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tentang Lapangan</h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed">
                        {{ $field->description ?: 'Lapangan futsal berkualitas tinggi dengan fasilitas lengkap dan modern. Dilengkapi dengan rumput sintetis premium, pencahayaan yang baik, dan sistem drainase yang sempurna untuk kenyamanan bermain Anda.' }}
                    </div>
                </div>

                <!-- Facilities -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Fasilitas</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @if($field->facilities && count($field->facilities) > 0)
                            @foreach($field->facilities as $facility)
                                <div class="flex items-center p-4 bg-blue-50 rounded-xl">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">{{ $facility }}</span>
                                </div>
                            @endforeach
                        @else
                            @foreach(['Rumput Sintetis', 'Pencahayaan LED', 'Parkir Luas', 'Kamar Mandi', 'Kantin', 'Sound System'] as $facility)
                                <div class="flex items-center p-4 bg-blue-50 rounded-xl">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">{{ $facility }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Jam Operasional</h2>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Jam Buka</span>
                                <span class="font-bold text-gray-900">{{ $field->open_time ?: '06:00' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Jam Tutup</span>
                                <span class="font-bold text-gray-900">{{ $field->close_time ?: '24:00' }}</span>
                            </div>
                            <div class="flex items-center justify-between md:col-span-2">
                                <span class="text-gray-600">Hari Operasional</span>
                                <div class="flex space-x-2">
                                    @if($field->operating_days && count($field->operating_days) > 0)
                                        @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'] as $day)
                                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium {{ in_array($day, $field->operating_days) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                                {{ strtoupper(substr($day, 0, 2)) }}
                                            </span>
                                        @endforeach
                                    @else
                                        @foreach(['SE', 'SE', 'RA', 'KA', 'JU', 'SA', 'MI'] as $day)
                                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-medium bg-blue-600 text-white">
                                                {{ $day }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location & Contact -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Lokasi & Kontak</h2>
                    <div class="bg-gray-50 rounded-xl p-6">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Alamat</p>
                                    <p class="text-gray-600">{{ $field->location }}</p>
                                </div>
                            </div>
                            
                            @if($field->payment_info)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">Info Pembayaran</p>
                                        <p class="text-gray-600">{{ $field->payment_info }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <!-- Booking Card -->
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 mb-6" x-data="bookingForm()">
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold text-blue-600 mb-2">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </div>
                            <div class="text-gray-500">per jam</div>
                        </div>

                        <!-- Date Picker -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                            <input 
                                type="date" 
                                x-model="selectedDate"
                                @change="fetchAvailableSlots()"
                                min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            >
                        </div>

                        <!-- Time Slots -->
                        <div class="mb-6" x-show="availableSlots.length > 0">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Waktu</label>
                            <div class="grid grid-cols-2 gap-2 max-h-60 overflow-y-auto">
                                <template x-for="slot in availableSlots" :key="slot">
                                    <button 
                                        @click="selectedSlot = slot"
                                        :class="selectedSlot === slot ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
                                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                        x-text="slot"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loading" class="text-center py-8">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            <p class="text-gray-500 mt-2">Memuat slot waktu...</p>
                        </div>

                        <!-- No Slots Available -->
                        <div x-show="selectedDate && !loading && availableSlots.length === 0" class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-gray-500">Tidak ada slot tersedia untuk tanggal ini</p>
                        </div>

                        <!-- Book Button -->
                        <div class="space-y-4">
                            @auth
                                <button 
                                    @click="bookNow()"
                                    :disabled="!selectedDate || !selectedSlot || loading"
                                    :class="selectedDate && selectedSlot && !loading ? 'bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                    class="w-full px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg"
                                >
                                    <span x-show="!loading">Booking Sekarang</span>
                                    <span x-show="loading" class="flex items-center justify-center">
                                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-2"></div>
                                        Processing...
                                    </span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="block w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white text-center px-6 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    Login untuk Booking
                                </a>
                            @endauth
                            
                            <div class="text-center">
                                <p class="text-sm text-gray-500">
                                    Max {{ $field->max_hours_per_booking ?: 3 }} jam per booking
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Field Info -->
                    <div class="bg-blue-50 rounded-2xl p-6">
                        <h3 class="font-bold text-blue-900 mb-4">Info Penting</h3>
                        <div class="space-y-3 text-sm text-blue-700">
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Konfirmasi otomatis setelah pembayaran</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Bisa cancel maksimal 2 jam sebelum jadwal</span>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Customer service 24/7 siap membantu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function bookingForm() {
    return {
        selectedDate: '',
        selectedSlot: null,
        availableSlots: [],
        loading: false,

        async fetchAvailableSlots() {
            if (!this.selectedDate) return;
            
            this.loading = true;
            this.availableSlots = [];
            this.selectedSlot = null;

            try {
                const response = await fetch(`{{ route('fields.slots', $field->id) }}?date=${this.selectedDate}`);
                const data = await response.json();
                this.availableSlots = data.slots || [];
            } catch (error) {
                console.error('Error fetching slots:', error);
                alert('Gagal memuat slot waktu. Silakan coba lagi.');
            } finally {
                this.loading = false;
            }
        },

        async bookNow() {
            if (!this.selectedDate || !this.selectedSlot) {
                alert('Silakan pilih tanggal dan waktu terlebih dahulu');
                return;
            }

            // Redirect to booking form
            window.location.href = `{{ route('bookings.create', $field->id) }}?date=${this.selectedDate}&time=${this.selectedSlot}`;
        }
    }
}
</script>
@endpush
@endsection