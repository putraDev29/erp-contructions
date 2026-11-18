@if(session('success') || session('status'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-xs transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600 flex-shrink-0"></i>
            <span class="text-xs sm:text-sm font-medium">{{ session('success') ?? session('status') }}</span>
        </div>
        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div x-data="{ show: true }" x-show="show"
         class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between shadow-xs transition-all">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-octagon" class="w-5 h-5 text-rose-600 flex-shrink-0"></i>
            <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
        </div>
        <button @click="show = false" class="text-rose-500 hover:text-rose-700 p-1">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
@endif

@if($errors->any())
    <div x-data="{ show: true }" x-show="show"
         class="mx-4 sm:mx-6 lg:mx-8 mt-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xs transition-all">
        <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2 font-semibold text-xs sm:text-sm text-amber-800">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                <span>Terdapat kesalahan pengisian form:</span>
            </div>
            <button @click="show = false" class="text-amber-500 hover:text-amber-700 p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <ul class="list-disc list-inside text-xs text-amber-800 space-y-1 ml-6">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif
