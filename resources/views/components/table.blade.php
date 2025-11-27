@props(['headers' => [], 'rows' => [], 'actions' => true, 'empty' => 'No data available'])

<!-- Table Component -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @forelse ($headers as $header)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @empty
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                    @endforelse

                    @if ($actions)
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($rows as $row)
                    <tr class="hover:bg-gray-50 transition-colors">
                        @if (is_array($row) || is_object($row))
                            @foreach ($row as $key => $value)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $value }}
                                </td>
                            @endforeach
                        @else
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" colspan="{{ count($headers) ?: 1 }}">
                                {{ $row }}
                            </td>
                        @endif

                        @if ($actions)
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @yield('row-actions')
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-12 text-center" colspan="{{ count($headers) + ($actions ? 1 : 0) }}">
                            <div class="text-gray-400">
                                <i class="ph ph-inbox text-4xl mb-2"></i>
                                <p class="text-sm">{{ $empty }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (isset($pagination))
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pagination }}
        </div>
    @endif
</div>