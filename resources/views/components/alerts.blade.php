@if (session('success'))
    <div class="bg-green-200 text-green-800 p-6 rounded-lg flex flex-col items-center mb-4" role="alert">
        <div class="text-4xl mb-2">
            <i data-lucide="check" class="w-5 h-5"></i>
        </div>
        <div class="mt-1">
            <span class="block sm:inline"> {{ session('success') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-800 p-6 rounded-lg flex flex-col items-center mb-4" role="alert">
        <div class="text-4xl mb-2">
            <i data-lucide="circle-x" class="w-5 h-5"></i>
        </div>
        <h3 class="font-semibold">Data Gagal Disimpan!</h3>
    </div>
@endif
