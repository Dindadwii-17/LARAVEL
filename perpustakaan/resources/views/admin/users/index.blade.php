<x-app-layout>
    <!-- Content Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Daftar Pengguna</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola data anggota dan administrator perpustakaan.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Pengguna Baru
        </a>
    </div>

    <div class="bg-white rounded-3xl card-shadow border border-slate-100 overflow-hidden">
        <div class="header-gradient px-8 py-5 text-white flex items-center justify-between">
            <h3 class="font-bold">Daftar Anggota</h3>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="bg-white/20 px-3 py-1 rounded-full text-blue-50">Total: {{ $users->total() }} Pengguna</span>
            </div>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="px-6 py-4 w-16 text-center">No</th>
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">NIM / ID</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-6 text-sm font-bold text-slate-400 text-center">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex items-center gap-4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E0F2FE&color=0284C7&bold=true" class="w-10 h-10 rounded-full border border-blue-100">
                                        <div>
                                            <div class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-sm font-bold text-slate-700">
                                    {{ $user->nim ?? '-' }}
                                </td>
                                <td class="px-6 py-6 text-sm text-slate-500">
                                    {{ $user->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-6">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.users.show', $user) }}" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-blue-600 hover:border-blue-100 hover:bg-blue-50 transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2.5 bg-white border border-slate-200 text-slate-400 rounded-xl hover:text-amber-600 hover:border-amber-100 hover:bg-amber-50 transition-all shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
                                <td colspan="6" class="px-6 py-20 text-center text-slate-400 italic">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
