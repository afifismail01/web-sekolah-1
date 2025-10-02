<x-filament-panels::page>
    <div class="p-6 bg-white rounded-xl shadow-md dark:bg-gray-800">
        <div class="flex flex-col items-center justify-center space-y-4 p-6">
            <div class="text-center">
                <h2 class="text-lg font-bold mb-4">Tahapan Pendaftaran Saat Ini</h2>

                @if ($activeStep)
                    <p><strong>Tahapan :</strong>{{ $activeStep->stage_name->value }}</p>
                    <p><strong>Periode :</strong>{{ $activeStep->start_date->format('d M Y') }} -
                        {{ $activeStep->end_date->format('d M Y') }}</p>
                @else
                    <p>Tidak ada tahapan aktif saat ini</p>
                @endif
                <div class="mt-2">
                    <a href="{{ route('filament.admin.pages.edit-stage') }}"
                        class="inline-block bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 transition">Edit</a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
