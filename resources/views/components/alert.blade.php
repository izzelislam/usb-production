<!-- Flash Messages Alert -->
@if(session()->has('success'))
    <div data-alert class="fixed top-20 right-4 z-50 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md">
        <i class="ph ph-check-circle text-2xl text-green-600"></i>
        <div>
            <p class="font-semibold">Success!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-400 hover:text-green-600">
            <i class="ph ph-x text-xl"></i>
        </button>
    </div>
@endif

@if(session()->has('error'))
    <div data-alert class="fixed top-20 right-4 z-50 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md">
        <i class="ph ph-x-circle text-2xl text-red-600"></i>
        <div>
            <p class="font-semibold">Error!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-400 hover:text-red-600">
            <i class="ph ph-x text-xl"></i>
        </button>
    </div>
@endif

@if(session()->has('warning'))
    <div data-alert class="fixed top-20 right-4 z-50 bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md">
        <i class="ph ph-warning text-2xl text-yellow-600"></i>
        <div>
            <p class="font-semibold">Warning!</p>
            <p class="text-sm">{{ session('warning') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto text-yellow-400 hover:text-yellow-600">
            <i class="ph ph-x text-xl"></i>
        </button>
    </div>
@endif

@if(session()->has('info'))
    <div data-alert class="fixed top-20 right-4 z-50 bg-blue-50 border border-blue-200 text-blue-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md">
        <i class="ph ph-info text-2xl text-blue-600"></i>
        <div>
            <p class="font-semibold">Info!</p>
            <p class="text-sm">{{ session('info') }}</p>
        </div>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto text-blue-400 hover:text-blue-600">
            <i class="ph ph-x text-xl"></i>
        </button>
    </div>
@endif

@error($errors)
    @foreach($errors->all() as $error)
        <div data-alert class="fixed top-20 right-4 z-50 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 min-w-[300px] max-w-md">
            <i class="ph ph-x-circle text-2xl text-red-600"></i>
            <div>
                <p class="font-semibold">Validation Error!</p>
                <p class="text-sm">{{ $error }}</p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-400 hover:text-red-600">
                <i class="ph ph-x text-xl"></i>
            </button>
        </div>
    @endforeach
@enderror