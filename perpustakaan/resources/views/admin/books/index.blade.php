<x-app-layout>
    <!-- Content Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Katalog Buku</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola koleksi buku perpustakaan Anda dengan mudah.</p>
        </div>
        <a href="{{ route('admin.books.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Buku Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl card-shadow border border-slate-100 overflow-hidden">
        <div class="header-gradient px-8 py-5 text-white flex items-center justify-between">
            <h3 class="font-bold">Daftar Koleksi</h3>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="bg-white/20 px-3 py-1 rounded-full text-blue-50">Total: {{ $books->count() }} Buku</span>
            </div>
        </div>
        
        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 w-16">No</th>
                            <th class="px-6 py-4 w-24 text-center">Cover</th>
                            <th class="px-6 py-4">Informasi Buku</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4 text-center">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($books as $book)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-6 text-sm font-bold text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="w-16 h-20 bg-slate-100 rounded-xl border border-slate-200 shadow-sm flex items-center justify-center overflow-hidden group-hover:scale-105 transition-transform">
                                        @if($book->cover)
                                            <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $book->title }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-slate-400 font-medium">{{ $book->author }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">ISBN: {{ $book->isbn ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="px-4 py-1.5 bg-sky-100 text-sky-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                        {{ $book->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-lg font-bold text-slate-800">{{ $book->quantity }}</span>
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Eksemplar</span>
                                    </div>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.books.edit', $book) }}" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
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
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 italic">Belum ada data buku dalam koleksi Anda.</p>
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

