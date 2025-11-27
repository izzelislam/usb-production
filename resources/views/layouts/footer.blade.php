<!-- FOOTER -->
<footer class="bg-white border-t border-gray-200 py-4 px-6">
    <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-600">
        <div class="mb-2 sm:mb-0">
            &copy; {{ date('Y') }} USB Cake Production. All rights reserved.
        </div>
        <div class="flex items-center gap-4">
            <span>Version 1.0.0</span>
            <span class="text-gray-400">|</span>
            <span>{{ now()->format('H:i') }}</span>
        </div>
    </div>
</footer>