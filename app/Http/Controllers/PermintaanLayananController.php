<?php

namespace App\Http\Controllers;

use App\Models\PermintaanLayanan;
use App\Models\RiwayatPenelitian;
use App\Models\Pnbp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermintaanLayananController extends Controller
{
 // 1. Menampilkan data permintaan (Halaman Index Admin)
public function index()
{
    $user = Auth::user();

    if ($user->role->nama_role == 'Customer') {
        $permintaans = PermintaanLayanan::with('laporanHasil')
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('front.index', compact('permintaans'));
    }

    // Tampilkan hanya permintaan yang belum selesai (sedang diproses atau diverifikasi)
    $query = PermintaanLayanan::with('user')
        ->whereIn('status', ['sedang diproses', 'diverifikasi'])
        ->orderBy('created_at', 'desc');
    
    // Search functionality
    $search = request('search');
    if ($search) {
        $query->where(function($builder) use ($search) {
            $builder->where('id_permintaan', 'like', '%' . $search . '%')
                    ->orWhere('pemohon', 'like', '%' . $search . '%')
                    ->orWhere('jenis_permintaan', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
        });
    }
    
    // Status filter
    $status = request('status');
    if ($status && $status !== '') {
        $query->where('status', $status);
    }
    
    $laporans = $query->paginate(10);
    
    return view('Admin.permintaanlayanan.index', compact('laporans', 'search', 'status'));
}

// 2. Menampilkan detail
public function show($id)
{
    $permintaan = PermintaanLayanan::with(['dokumens', 'pnbp', 'laporanHasil', 'riwayats'])->findOrFail($id);
    
    return view('Admin.permintaanlayanan.show', compact('permintaan'));
}

// 4. Menampilkan form untuk edit
public function edit($id)
{
    $permintaan = PermintaanLayanan::findOrFail($id);
    
    // SESUAIKAN DENGAN FOTO: Admin/permintaanlayanan/edit
    return view('Admin.permintaanlayanan.edit', compact('permintaan'));
}
    // 5. Menyimpan perubahan permintaan (Admin)
    public function update(Request $request, $id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        $request->validate([
            'jenis_permintaan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'pemohon' => 'required|string|max:100',
            'tanggal_permintaan' => 'required|date',
            'status' => 'required|in:sedang diproses,diverifikasi,selesai',
            'file_layanan' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:5120', 
        ]);

        $data = [
            'jenis_permintaan' => $request->jenis_permintaan,
            'no_hp' => $request->no_hp,
            'pemohon' => $request->pemohon,
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'status' => $request->status,
        ];

        // Handle file upload jika ada file baru
        if ($request->hasFile('file_layanan')) {
            // Hapus file lama jika ada
            if ($permintaan->file_layanan && file_exists(public_path('uploads/permintaan/' . $permintaan->file_layanan))) {
                unlink(public_path('uploads/permintaan/' . $permintaan->file_layanan));
            }

            $file = $request->file('file_layanan');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/permintaan'), $nama_file);
            $data['file_layanan'] = $nama_file;
        }

        $permintaan->update($data);

        return redirect()->route('admin.permintaan.index')->with('success', 'Permintaan berhasil diperbarui!');
    }

    // 6. Update Status (Untuk Petugas/Admin)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:sedang diproses,diverifikasi,selesai'
        ]);

        $permintaan = PermintaanLayanan::findOrFail($id);
        $permintaan->status = $request->status;
        $permintaan->save(); 

        return redirect()->route('admin.permintaan.index')->with('success', 'Status berhasil diperbarui!');
    }
    
    // 8. Menyimpan Permintaan Baru (Customer) + Auto-create PNBP dengan Tarif Fixed 100rb
    public function store(Request $request)
    {
        $request->validate([
            'jenis_permintaan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'file_layanan' => 'required|file|mimes:pdf,doc,docx,zip,rar|max:5120',
            'bukti_bayar' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $nama_file = null;
        if ($request->hasFile('file_layanan')) {
            $file = $request->file('file_layanan');
            $nama_file = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/permintaan'), $nama_file);
        }

        // Handle bukti pembayaran upload
        $bukti_bayar = null;
        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $bukti_bayar = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/pnbp'), $bukti_bayar);
        }

        // Tarif fixed untuk semua permintaan = Rp 100.000
        $tarif_fixed = 100000;

        // 1. Buat Permintaan Layanan
        $permintaan = PermintaanLayanan::create([
            'id_user' => Auth::id(), 
            'pemohon' => Auth::user()->nama, 
            'jenis_permintaan' => $request->jenis_permintaan,
            'no_hp' => $request->no_hp,
            'file_layanan' => $nama_file,
            'status' => 'sedang diproses',
            'tanggal_permintaan' => now()->toDateString() 
        ]);

        // 2. Otomatis buat PNBP (Tagihan) dengan tarif fixed 100rb
        Pnbp::create([
            'id_permintaan' => $permintaan->id_permintaan,
            'total_biaya' => $tarif_fixed,
            'jumlah_bayar' => 0,
            'sisa_tagihan' => $tarif_fixed,
            'status_pembayaran' => 'Belum Dibayar',
            'bukti_bayar' => $bukti_bayar
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Permintaan berhasil dikirim');
    }

    public function downloadHasilCustomer($id)
    {
        $permintaan = PermintaanLayanan::with('laporanHasil')->findOrFail($id);

        abort_unless($permintaan->id_user === Auth::id(), 403);

        $laporan = $permintaan->laporanHasil;
        abort_unless($laporan && $laporan->file_hasil, 404);

        $filePath = public_path('uploads/laporan/' . $laporan->file_hasil);
        abort_unless(is_file($filePath), 404);

        return response()->download($filePath);
    }

    // 9. Menghapus Permintaan
    public function destroy($id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        // Hapus file jika ada
        if ($permintaan->file_layanan && file_exists(public_path('uploads/permintaan/' . $permintaan->file_layanan))) {
            unlink(public_path('uploads/permintaan/' . $permintaan->file_layanan));
        }

        $permintaan->delete();
        return redirect()->route('admin.permintaan.index')->with('success', 'Permintaan berhasil dihapus!');
    }

    // 10. Menampilkan data permintaan untuk Kepala Lab
    public function indexKepalaLab(Request $request)
    {
        $query = PermintaanLayanan::with('user')
            ->whereIn('status', ['sedang diproses', 'diverifikasi'])
            ->orderBy('created_at', 'desc');
        
        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where(function($builder) use ($search) {
                $builder->where('id_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('pemohon', 'like', '%' . $search . '%')
                        ->orWhere('jenis_permintaan', 'like', '%' . $search . '%')
                        ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }
        
        // Status filter
        $status = $request->get('status');
        if ($status && $status !== '') {
            $query->where('status', $status);
        }
        
        $permintaans = $query->paginate(5)->appends($request->query());
        
        return view('KepalaLab.PermintaanLayanan.index', compact('permintaans', 'search', 'status'));
    }

    public function editKepalaLab($id)
    {
        $permintaan = PermintaanLayanan::with(['user', 'laporanHasil', 'riwayats'])->findOrFail($id);

        if ($permintaan->status !== 'diverifikasi' && $permintaan->status !== 'selesai') {
            return redirect()->route('kepala.permintaan')->with('error', 'Permintaan ini belum bisa diperiksa oleh Kepala Lab.');
        }

        return view('KepalaLab.PermintaanLayanan.edit', compact('permintaan'));
    }

    public function updateKepalaLab(Request $request, $id)
    {
        $permintaan = PermintaanLayanan::findOrFail($id);

        if ($permintaan->status !== 'diverifikasi') {
            return redirect()->route('kepala.permintaan')->with('error', 'Hanya permintaan berstatus diverifikasi yang bisa diubah menjadi selesai.');
        }

        $request->validate([
            'status' => 'required|in:selesai',
        ]);

        $permintaan->update([
            'status' => 'selesai',
        ]);

        // Preferensi: gunakan id_user dari riwayat terbaru yang memiliki id_user (petugas yang mengunggah hasil).
        // Jika tidak ada riwayat dengan id_user, biarkan null.
        $petugasId = $permintaan->riwayats()->whereNotNull('id_user')->latest('updated_at')->value('id_user');

        RiwayatPenelitian::updateOrCreate(
            ['id_permintaan' => $permintaan->id_permintaan],
            [
                'id_permintaan' => $permintaan->id_permintaan,
                'id_user' => $petugasId,
                'nama_laporan' => $permintaan->jenis_permintaan,
                'tanggal_selesai' => now(),
                'status' => 'selesai',
            ]
        );

        return redirect()->route('kepala.permintaan')->with('success', 'Permintaan berhasil ditandai selesai oleh Kepala Lab.');
    }
}