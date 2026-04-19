{{-- Wrapper Utama dengan Latar Belakang Bertema (Contoh: Latar belakang abu-abu sangat muda/silver-white) --}}
<div class="min-h-screen bg-[#F8F9FA] flex flex-col items-center justify-center p-4 antialiased"
     {{-- Inisialisasi State Alpine.js --}}
     x-data="{ passwordFocused: false }">

    {{-- Container Kartu Login --}}
     <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-10 border border-[#84A59D]/10 relative mt-16 transition-all duration-300">
        
        {{-- --- AREA INTERAKTIF CHIBI FRIEREN --- --}}
        {{-- Posisikan di atas tengah kartu --}}
        <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-32 h-32 flex items-end justify-center">
            
            {{-- Gambar 1: Frieren Melihat (Default) --}}
            {{-- Tampil jika passwordFocused adalah FALSE --}}
            <img x-show="!passwordFocused" 
                 src="{{ asset('images/chibi-frieren-open.png') }}" 
                 alt="Frieren Default" 
                 class="h-full object-contain transform hover:scale-105 transition duration-300"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100">

            {{-- Gambar 2: Frieren Menutup Mata (Saat Password Focused) --}}
            {{-- Tampil jika passwordFocused adalah TRUE --}}
            <img x-show="passwordFocused" 
                 src="{{ asset('images/chibi-frieren-blink.png') }}" 
                 alt="Frieren Covering Eyes" 
                 class="h-full object-contain"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 style="display: none;"> {{-- Mencegah flicker saat load awal --}}
        </div>
        {{-- --------------------------------------- --}}

        {{-- Header Tampilan --}}
        <div class="text-center mb-10 pt-10">
            {{-- Gunakan Serif Font untuk kesan Ethereal/Magis (Ganti 'font-serif' jika kamu pakai font kustom) --}}
            <h1 class="text-4xl font-extrabold text-[#334155] tracking-tight font-serif">
                STOKIFY<span class="text-[#84A59D]">v2</span>
            </h1>
            {{-- Aksen Garis Emas Halus --}}
            <div class="w-20 h-0.5 bg-[#D4AF37] mx-auto mt-3 rounded-full"></div>
            <p class="text-gray-500 mt-4 text-sm font-light tracking-wide">Magical ERP & POS Management</p>
        </div>

        {{-- Area Error (Livewire) --}}
        @error('login') 
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-center border border-red-200 text-sm font-medium">
                {{ $message }}
            </div> 
        @enderror

        {{-- Form Login (Livewire) --}}
        <form wire:submit="prosesLogin" class="space-y-6">
            
            {{-- Input Username --}}
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 tracking-wide group-focus-within:text-[#84A59D]">Username</label>
                <div class="relative">
                    {{-- Ikon Minimalis Line (Contoh: User) --}}
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#84A59D]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input type="text" 
                           wire:model="username" 
                           placeholder="Enter your username"
                           class="w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#84A59D]/30 focus:border-[#84A59D] bg-white transition duration-150 text-gray-800 placeholder:text-gray-300">
                </div>
                @error('username') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Input Password --}}
            <div class="group">
                <label class="block text-sm font-semibold text-gray-700 mb-2 tracking-wide group-focus-within:text-[#84A59D]">Password</label>
                <div class="relative">
                    {{-- Ikon Minimalis Line (Contoh: Lock) --}}
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 group-focus-within:text-[#84A59D]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    
                    {{-- --- LOGIKA PENGUBAH STATE ALPINE.JS --- --}}
                    {{-- Ketika Input ini di-fokus-kan, set passwordFocused = true --}}
                    {{-- Ketika Input ini kehilangan fokus (blur), set passwordFocused = false --}}
                    <input type="password" 
                           wire:model="password" 
                           placeholder="••••••••"
                           @focus="passwordFocused = true"
                           @blur="passwordFocused = false"
                           class="w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#84A59D]/30 focus:border-[#84A59D] bg-white transition duration-150 text-gray-800 placeholder:text-gray-300">
                </div>
                @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-4">
                <button type="submit" 
                        class="w-full bg-[#84A59D] hover:bg-[#73928a] text-white font-bold py-4 px-4 rounded-xl shadow-lg shadow-[#84A59D]/20 transition duration-300 ease-in-out transform hover:-translate-y-0.5 tracking-wider text-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h12m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    MASUK SISTEM
                </button>
            </div>
        </form>

        {{-- Footer Tambahan (Opsional, Simpel) --}}
        <div class="text-center mt-10">
            <p class="text-xs text-gray-400 font-light">&copy; {{ date('Y') }} Developed by Roger Jeremy.</p>
        </div>

    </div>
</div>