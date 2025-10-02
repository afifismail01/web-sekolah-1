@extends('layouts.student')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">Selamat Datang, {{ Auth::user()->name }} !</h2>
    <div class="bg-white shadow rounded p-4">
        <div class="bg-green-200 text-green-800 rounded-lg p-6 flex flex-col items-center">
            {{-- Ikon --}}
            <div class="text-4xl mb-2">
                <i data-lucide="clock-4"></i>
            </div>

            {{-- teks --}}
            <h3 class="text-xl font-bold mb-2">Tahapan Saat Ini</h3>
            <p class="text-center font-medium">
                <span class="font-medium">Tahap: </span>{{ $activeStage->stage_name->value }}
            </p>
            <p class="text-center font-medium mb-4">
                <span class="font-medium">Periode: </span>{{ $activeStage->start_date->format('d M Y') }} -
                {{ $activeStage->end_date->format('d M Y') }}
            </p>
            @if ($activeStage?->stage_name === App\Enums\StageNameEnum::REGISTRATION)
                <p class="font-medium">Mohon selesaikan pembayaran dan input formulir sebelum masa tahap "Input data dan
                    Pembayaran"
                    berakhir</p>
            @endif
        </div>

    </div>
    </div>
@endsection
