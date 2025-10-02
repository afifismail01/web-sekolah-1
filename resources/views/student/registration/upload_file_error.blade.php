@extends('layouts.student')
@section('content')
    <div class="bg-white shadow-md rounded-xl p-4 max-w-4xl mx-auto flex flex-col items-center">
        <h2 class="text-2xl font-bold mb-4">
            PERHATIAN !
        </h2>
        <p class="italic text-center mb-4">Anda perlu melengkapi data diri anda terlebih dahulu agar dapat mengakses
            halaman
            upload
            berkas</p>
        <a href="{{ route('student.personalData') }}" class="bg-blue-500 hover:bg-blue-600 text-white rounded p-4 ">
            Menuju ke halaman data diri
        </a>
    </div>
@endsection
