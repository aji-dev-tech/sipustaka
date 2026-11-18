<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    // Catatan: binding {anggota} di route sudah dibatasi ke role 'siswa'
    // (lihat AppServiceProvider), jadi edit/update/destroy tidak bisa menyentuh akun admin.

    public function index()
    {
        $anggotas = User::where('role', 'siswa')->latest()->paginate(10);
        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis_nip' => 'required|string|max:50|unique:users,nis_nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'siswa';

        User::create($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggota)
    {
        return view('admin.anggota.edit', ['anggota' => $anggota]);
    }

    public function update(Request $request, User $anggota)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis_nip' => 'required|string|max:50|unique:users,nis_nip,' . $anggota->id,
            'email' => 'required|email|unique:users,email,' . $anggota->id,
            'password' => 'nullable|string|min:6',
            'no_telp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $anggota->update($validated);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $anggota)
    {
        // Riwayat peminjaman + denda adalah catatan yang harus tetap ada,
        // jadi anggota yang pernah meminjam tidak boleh dihapus (sama seperti aturan di Buku).
        if ($anggota->peminjaman()->exists()) {
            return redirect()->route('admin.anggota.index')
                ->with('error', 'Anggota tidak bisa dihapus karena memiliki riwayat peminjaman.');
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
