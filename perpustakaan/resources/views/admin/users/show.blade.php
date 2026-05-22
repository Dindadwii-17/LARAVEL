<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Akun') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">NIM</p>
                                    <p class="text-lg">{{ $user->nim ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nama Lengkap</p>
                                    <p class="text-lg">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-lg">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Role</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Kontak & Alamat</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">No. Telp</p>
                                    <p class="text-lg">{{ $user->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Alamat</p>
                                    <p class="text-lg">{{ $user->address ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Terdaftar Sejak</p>
                                    <p class="text-lg">{{ $user->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t flex justify-end">
                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Edit Akun
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                Hapus Akun
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
