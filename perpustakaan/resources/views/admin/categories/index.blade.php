<x-app-layout>
    <!-- Content Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Manajemen Kategori</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola kategori buku untuk pengorganisasian koleksi yang lebih baik.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Kategori Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl card-shadow border border-slate-100 overflow-hidden max-w-4xl">
        <div class="header-gradient px-8 py-5 text-white flex items-center justify-between">
            <h3 class="font-bold">Daftar Kategori</h3>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="bg-white/20 px-3 py-1 rounded-full text-blue-50">Total: {{ $categories->count() }} Kategori</span>
            </div>
        </div>
        
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 w-20">No</th>
                            <th class="px-6 py-4">Nama Kategori</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-6 text-sm font-bold text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold text-lg">
                                            {{ substr($category->name, 0, 1) }}
                                        </div>
                                        <div class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                            {{ $category->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-red-600 hover:border-red-100 hover:bg-red-50 transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 italic">Belum ada data kategori.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

