@php
    $property = 'tampilan'.$kategori;
    $uploadProperty = 'logo'.$kategori;
    $savedLogo = $kategori === 'Putra' ? $logoPutraTersimpan : $logoPutriTersimpan;
@endphp

<div class="mb-6 border-t border-gray-200 pt-6">
    <h3 class="text-base font-semibold text-gray-800 mb-4">Logo, Judul, dan Subjudul {{ $kategori }}</h3>
    <div class="mb-6">
        <label for="{{ $uploadProperty }}" class="block text-sm font-medium text-gray-700 mb-2">Logo {{ $kategori }}</label>
        <img src="{{ $savedLogo }}" alt="Logo rapor {{ $kategori }} yang tersimpan" class="mb-3 h-20 w-20 object-contain">
        <input type="file" id="{{ $uploadProperty }}" wire:model="{{ $uploadProperty }}" accept="image/png,image/jpeg" class="block w-full text-sm text-gray-700">
        <p class="mt-1 text-sm text-gray-500">PNG/JPG, maksimal 2 MB dan 2000 × 2000 piksel. Logo baru diterapkan setelah disimpan.</p>
        <p wire:loading wire:target="{{ $uploadProperty }}" class="mt-1 text-sm text-blue-600">Mengunggah logo...</p>
        @error($uploadProperty)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($kolomTampilan as $key => $field)
            <div wire:key="teks-{{ $kategori }}-{{ $key }}">
                <label for="{{ $property }}-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-2">{{ $field['label'] }}</label>
                <textarea id="{{ $property }}-{{ $key }}" wire:model="{{ $property }}.{{ $key }}" dir="auto" rows="2"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error($property.'.'.$key) border-red-500 @enderror"></textarea>
                @error($property.'.'.$key)
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
    <p class="mt-3 text-sm text-gray-500">Judul dan subjudul dapat ditulis dalam bahasa Arab atau Latin. Teks lainnya mengikuti format rapor yang sama untuk Putra dan Putri.</p>
</div>
