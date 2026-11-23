<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GuestController;
use App\Models\Department;
use App\Models\Guest;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminGuestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $status = $request->query('status');
        $deptId = $request->query('department_id');
        $city = $request->query('city_regency');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Guest::with('department')->latest('id');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('institution', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($deptId) {
            $query->where('department_id', $deptId);
        }

        if ($city) {
            $query->where('city_regency', $city);
        }

        if ($startDate) {
            $query->whereDate('visit_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('visit_date', '<=', $endDate);
        }

        $guests = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();
        $regions = GuestController::REGION_LIST;

        return view('admin.guests.index', compact(
            'guests',
            'departments',
            'regions',
            'search',
            'status',
            'deptId',
            'city',
            'startDate',
            'endDate'
        ));
    }

    public function show(Guest $guest)
    {
        $guest->load('department');
        return view('admin.guests.show', compact('guest'));
    }

    public function updateStatus(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,sedang_bertemu,selesai,dibatalkan',
            'officer_notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'officer_notes' => $validated['officer_notes'] ?? $guest->officer_notes,
        ];

        if ($validated['status'] === 'selesai' && !$guest->check_out_time) {
            $updateData['check_out_time'] = Carbon::now()->toTimeString();
        }

        $guest->update($updateData);

        return back()->with('success', "Status kunjungan {$guest->ticket_number} berhasil diperbarui menjadi {$guest->status_label}.");
    }

    public function destroy(Guest $guest)
    {
        $ticket = $guest->ticket_number;
        $guest->delete();

        return redirect()->route('admin.guests.index')
                         ->with('success', "Data kunjungan {$ticket} berhasil dihapus.");
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');
        $deptId = $request->query('department_id');

        $query = Guest::with('department')->orderBy('visit_date', 'asc')->orderBy('check_in_time', 'asc');

        if ($startDate) {
            $query->whereDate('visit_date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('visit_date', '<=', $endDate);
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($deptId) {
            $query->where('department_id', $deptId);
        }

        $guests = $query->get();
        $filename = 'buku_tamu_bgtk_sumbar_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($guests) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens it with proper encoding
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No. Tiket',
                'Tanggal',
                'Jam Masuk',
                'Jam Keluar',
                'Nama Tamu',
                'NIP / NIK / NUPTK',
                'No. Telepon / WA',
                'Email',
                'Asal Instansi / Sekolah',
                'Jabatan',
                'Kabupaten / Kota',
                'Tujuan Bagian / Tim Kerja',
                'Pejabat / Staf Dituju',
                'Keperluan Kunjungan',
                'Jumlah Rombongan',
                'Status Kunjungan',
                'Rating Kepuasan (1-5)',
                'Kritik / Saran',
                'Catatan Petugas',
            ]);

            foreach ($guests as $g) {
                fputcsv($handle, [
                    $g->ticket_number,
                    $g->visit_date ? $g->visit_date->format('d/m/Y') : '-',
                    $g->check_in_time,
                    $g->check_out_time ?? '-',
                    $g->name,
                    $g->identity_number ?? '-',
                    $g->phone,
                    $g->email ?? '-',
                    $g->institution,
                    $g->position,
                    $g->city_regency,
                    $g->department ? $g->department->name : '-',
                    $g->destination_person ?? '-',
                    $g->purpose,
                    $g->companion_count,
                    $g->status_label,
                    $g->satisfaction_rating ?? '-',
                    $g->satisfaction_feedback ?? '-',
                    $g->officer_notes ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function printReport(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());
        $deptId = $request->query('department_id');
        $status = $request->query('status');

        $query = Guest::with('department')
            ->whereDate('visit_date', '>=', $startDate)
            ->whereDate('visit_date', '<=', $endDate)
            ->orderBy('visit_date', 'asc')
            ->orderBy('check_in_time', 'asc');

        if ($deptId) {
            $query->where('department_id', $deptId);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $guests = $query->get();
        $institutionName = Setting::get('institution_name', 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat');
        $ministryName = Setting::get('ministry_name', 'Kementerian Pendidikan Dasar dan Menengah RI');
        $address = Setting::get('address');
        $headOfInstitution = Setting::get('head_of_institution');
        $nipHead = Setting::get('nip_head');

        return view('admin.guests.print', compact(
            'guests',
            'startDate',
            'endDate',
            'institutionName',
            'ministryName',
            'address',
            'headOfInstitution',
            'nipHead'
        ));
    }
}
