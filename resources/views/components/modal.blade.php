<!-- MODAL COMPONENT - Default Confirmation Modal -->
<div id="demo-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="toggleModal('demo-modal')"></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Header -->
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="ph ph-info text-xl text-primary-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">@yield('modal-title', 'Konfirmasi Aksi')</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">@yield('modal-content', 'Apakah Anda yakin ingin menyimpan perubahan ini? Data akan diperbarui secara otomatis di database.')</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    @yield('modal-actions')
                    <button type="button" class="inline-flex w-full justify-center rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto" onclick="toggleModal('demo-modal')">
                        @yield('modal-confirm', 'Simpan Data')
                    </button>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" onclick="toggleModal('demo-modal')">
                        @yield('modal-cancel', 'Batal')
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 z-[60] hidden" aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="toggleModal('delete-modal')"></div>

    <!-- Modal panel -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Header with Icon -->
                <div class="bg-white px-6 pb-4 pt-6 sm:p-8 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-12 sm:w-12">
                            <i class="ph ph-warning text-3xl text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                            <h3 class="text-xl font-bold leading-6 text-gray-900" id="delete-modal-title">Hapus Karyawan</h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus karyawan <strong id="delete-item-name" class="text-gray-900"></strong>?</p>
                                <p class="text-sm text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan dan semua data terkait akan dihapus secara permanen.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer / Actions -->
                <div class="bg-gray-50 px-6 py-4 sm:flex sm:flex-row-reverse sm:px-8 gap-3">
                    <form id="delete-form" method="POST" action="" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center items-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/30 hover:bg-red-700 transition-all sm:w-auto">
                            <i class="ph ph-trash-simple text-lg"></i>
                            Ya, Hapus
                        </button>
                    </form>
                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all sm:mt-0 sm:w-auto" onclick="toggleModal('delete-modal')">
                        Batal
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Dynamic Modal Container for Custom Modals -->
<div id="modal-container"></div>

<script>
// Function to show delete confirmation modal
function showDeleteModal(url, itemName = '') {
    const form = document.getElementById('delete-form');
    const nameElement = document.getElementById('delete-item-name');
    
    if (form) {
        form.action = url;
    }
    
    if (nameElement && itemName) {
        nameElement.textContent = itemName;
    }
    
    toggleModal('delete-modal');
}

// Function to create dynamic modal
function showModal(title, content, confirmCallback, confirmText = 'Simpan', cancelText = 'Batal') {
    const container = document.getElementById('modal-container');
    container.innerHTML = `
        <div class="fixed inset-0 z-[60]" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">${title}</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">${content}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" onclick="this.closest('.fixed').remove(); ${confirmCallback}" class="inline-flex w-full justify-center rounded-lg bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto">
                                ${confirmText}
                            </button>
                            <button type="button" onclick="this.closest('.fixed').remove()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                ${cancelText}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}
</script>