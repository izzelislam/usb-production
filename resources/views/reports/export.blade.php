@extends('layouts.app')

@section('title', 'Export Data')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Pusat Export Data</h1>
        <p class="text-gray-600 mt-1">Unduh laporan dan data master dalam format Excel atau PDF.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($exportOptions as $option)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="p-3 bg-primary-50 rounded-lg text-primary-600">
                        <i class="{{ $option['icon'] }} text-2xl"></i>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $option['name'] }}</h3>
                <p class="text-sm text-gray-500 mb-6 h-10">{{ $option['description'] }}</p>
                
                <div class="flex flex-col gap-2">
                    @if(isset($option['route']))
                        <a href="{{ route($option['route']) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors text-sm font-medium border border-gray-200">
                            <i class="ph ph-eye text-lg"></i>
                            Lihat & Export
                        </a>
                    @else
                        <button disabled class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gray-50 text-gray-400 rounded-lg cursor-not-allowed text-sm font-medium border border-gray-200">
                            <i class="ph ph-lock text-lg"></i>
                            Segera Hadir
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
