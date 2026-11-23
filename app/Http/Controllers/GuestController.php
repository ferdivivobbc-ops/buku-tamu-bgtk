<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Guest;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    private const ESTIMATED_MINUTES_PER_VISITOR = 15;

    public const REGION_LIST = [
        'Kota Padang',
        'Kota Bukittinggi',
        'Kota Payakumbuh',
        'Kota Pariaman',
        'Kota Solok',
        'Kota Sawahlunto',
        'Kota Padang Panjang',
        'Kab. Agam',
        'Kab. Lima Puluh Kota',
        'Kab. Tanah Datar',
        'Kab. Padang Pariaman',
        'Kab. Pesisir Selatan',
        'Kab. Solok',
        'Kab. Solok Selatan',
        'Kab. Sijunjung',
        'Kab. Dharmasraya',
        'Kab. Pasaman',
        'Kab. Pasaman Barat',
        'Kab. Kepulauan Mentawai',
        'Luar Provinsi Sumatera Barat',
    ];

    public const POSITION_LIST = [
        'Guru (PNS / PPPK / Non-ASN)',
        'Kepala Sekolah',
        'Pengawas Sekolah',
        'Tenaga Administrasi / Operator Sekolah',
        'Pejabat / Staf Dinas Pendidikan',
        'Dosen / Akademisi LPTK',
        'Organisasi Profesi (PGRI, IGI, dsb.)',
        'Mitra Pembangunan / Komunitas Belajar',
        'Mahasiswa / Pelajar',
        'Masyarakat Umum / Tamu Lainnya',
    ];

    public const PURPOSE_LIST = [
        'Konsultasi PPG (Pendidikan Profesi Guru)',
        'Layanan Akun SIMPKB & Pengelolaan Kinerja PMM',
        'Manajemen Talenta',
        'BCKS',
        'UKKJ',
        'INFO GTK',
        'Koordinasi / Audiensi Kedinasan',
        'Penyerahan Berkas / Surat / Dokumen Dinas',
        'Mengikuti Pelatihan / Lokakarya / Bimtek BGTK',
        'Konsultasi Urusan Kepegawaian & Tata Usaha',
        'Kunjungan Studi Banding / Studi Tiru',
        'Keperluan Lainnya',
    ];

    /**
     * Tampilkan formulir buku tamu publik (Kiosk)
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $regions = self::REGION_LIST;
        $positions = self::POSITION_LIST;
        $purposes = self::PURPOSE_LIST;
        $runningText = Setting::get('running_text');
        $institutionName = Setting::get('institution_name', 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat');

        return view('guest.create', compact('departments', 'regions', 'positions', 'purposes', 'runningText', 'institutionName'));
    }

    /**
     * Tampilkan QR besar untuk layar TV dan ekspor ke flashdisk.
     */
    public function qrDisplay()
    {
        return view('guest.qr-display', [
            'formUrl' => route('guest.create'),
        ]);
    }

    /**
     * Simpan kunjungan tamu baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'identity_number' => 'nullable|string|max:50',
            'phone' => 'required|string|max:25',
            'email' => 'nullable|email|max:100',
            'institution' => 'required|string|max:150',
            'position' => 'required|string|max:100',
            'city_regency' => 'required|string|max:100',
            'purpose' => 'required|string|max:500',
            'department_id' => ['nullable', Rule::exists('departments', 'id')->where('is_active', true)],
            'destination_person' => 'nullable|string|max:150',
            'companion_count' => 'required|integer|min:1|max:100',
            'photo_data' => 'nullable|string',
            'photo_file' => 'nullable|image|max:5120',
            'signature_data' => 'nullable|string',
        ]);

        $ticketNumber = Guest::generateTicketNumber();
        $now = Carbon::now();

        $photoPath = null;
        if (!empty($validated['photo_data']) && str_starts_with($validated['photo_data'], 'data:image')) {
            $photoData = preg_replace('#^data:image/\w+;base64,#i', '', $validated['photo_data']);
            $decodedPhoto = base64_decode($photoData);
            if ($decodedPhoto !== false) {
                $filename = 'photos/' . $ticketNumber . '_' . time() . '.jpg';
                Storage::disk('public')->put($filename, $decodedPhoto);
                $photoPath = $filename;
            }
        } elseif ($request->hasFile('photo_file')) {
            $photoPath = $request->file('photo_file')->store('photos', 'public');
        }

        $signaturePath = null;
        if (!empty($validated['signature_data']) && str_starts_with($validated['signature_data'], 'data:image')) {
            $sigData = preg_replace('#^data:image/\w+;base64,#i', '', $validated['signature_data']);
            $decodedSig = base64_decode($sigData);
            if ($decodedSig !== false) {
                $sigFilename = 'signatures/' . $ticketNumber . '_' . time() . '.png';
                Storage::disk('public')->put($sigFilename, $decodedSig);
                $signaturePath = $sigFilename;
            }
        }

        $guest = Guest::create([
            'ticket_number' => $ticketNumber,
            'visit_date' => $now->toDateString(),
            'check_in_time' => $now->toTimeString(),
            'name' => $validated['name'],
            'identity_number' => $validated['identity_number'] ?? null,
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'institution' => $validated['institution'],
            'position' => $validated['position'],
            'city_regency' => $validated['city_regency'],
            'purpose' => $validated['purpose'],
            'department_id' => $validated['department_id'] ?? null,
            'destination_person' => $validated['destination_person'] ?? null,
            'companion_count' => $validated['companion_count'] ?? 1,
            'photo_path' => $photoPath,
            'signature_path' => $signaturePath,
            'status' => 'menunggu',
        ]);

        return redirect()->route('guest.success', ['ticket' => $guest->ticket_number])
                         ->with('success', 'Buku tamu berhasil diisi! Selamat datang di BGTK Sumatera Barat.');
    }

    /**
     * Halaman bukti registrasi / Tiket Kunjungan
     */
    public function success(string $ticket)
    {
        $guest = Guest::with('department')->where('ticket_number', $ticket)->firstOrFail();
        $institutionName = Setting::get('institution_name', 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat');
        $address = Setting::get('address');
        $phone = Setting::get('phone');
        $whatsapp = Setting::get('whatsapp_service');
        $queueForDepartment = static function () use ($guest) {
            return Guest::whereDate('visit_date', $guest->visit_date)
                ->when(
                    $guest->department_id,
                    fn ($query) => $query->where('department_id', $guest->department_id),
                    fn ($query) => $query->whereNull('department_id')
                );
        };
        $waitingAhead = $queueForDepartment()
            ->where('status', 'menunggu')
            ->where('id', '<', $guest->id)
            ->count();
        $currentlyInService = $queueForDepartment()
            ->where('status', 'sedang_bertemu')
            ->count();
        $queueAhead = $waitingAhead + $currentlyInService;
        $estimatedCallTime = Carbon::now()->addMinutes(
            $queueAhead * self::ESTIMATED_MINUTES_PER_VISITOR
        );

        return view('guest.success', compact(
            'guest',
            'institutionName',
            'address',
            'phone',
            'whatsapp',
            'queueAhead',
            'estimatedCallTime'
        ));
    }

    /**
     * Halaman Checkout Kunjungan & Survei Kepuasan (IKM)
     */
    public function checkout(Request $request)
    {
        $search = $request->query('q');
        $todayGuests = Guest::whereDate('visit_date', Carbon::today())
            ->whereNull('check_out_time')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('institution', 'like', "%{$search}%");
                });
            })
            ->latest('id')
            ->get();

        $selectedTicket = $request->query('ticket');
        $selectedGuest = null;
        if ($selectedTicket) {
            $selectedGuest = Guest::where('ticket_number', $selectedTicket)->first();
        }

        return view('guest.checkout', compact('todayGuests', 'selectedGuest', 'search'));
    }

    /**
     * Proses simpan checkout & rating kepuasan IKM
     */
    public function storeCheckout(Request $request)
    {
        $validated = $request->validate([
            'ticket_number' => 'required|string|exists:guests,ticket_number',
            'satisfaction_rating' => 'required|integer|between:1,5',
            'satisfaction_feedback' => 'nullable|string|max:500',
        ]);

        $guest = Guest::where('ticket_number', $validated['ticket_number'])->firstOrFail();
        
        $now = Carbon::now();
        $guest->update([
            'check_out_time' => $now->toTimeString(),
            'status' => 'selesai',
            'satisfaction_rating' => $validated['satisfaction_rating'],
            'satisfaction_feedback' => $validated['satisfaction_feedback'] ?? null,
            'rated_at' => $now,
        ]);

        return redirect()->route('guest.create')
                         ->with('success', 'Terima kasih atas kunjungan Anda dan penilaian yang diberikan! Semoga layanan kami memuaskan.');
    }

    /**
     * Layar Monitor Informasi Kunjungan Hari Ini (Kiosk TV Display di Lobby)
     */
    public function display()
    {
        $today = Carbon::today();
        $todayGuests = Guest::with('department')
            ->whereDate('visit_date', $today)
            ->latest('id')
            ->take(15)
            ->get();

        $totalToday = Guest::whereDate('visit_date', $today)->count();
        $waitingToday = Guest::whereDate('visit_date', $today)->where('status', 'menunggu')->count();
        $activeToday = Guest::whereDate('visit_date', $today)->where('status', 'sedang_bertemu')->count();
        $finishedToday = Guest::whereDate('visit_date', $today)->where('status', 'selesai')->count();
        $avgRating = Guest::whereNotNull('satisfaction_rating')->avg('satisfaction_rating') ?? 5.0;

        $runningText = Setting::get('running_text');
        $institutionName = Setting::get('institution_name', 'Balai Guru dan Tenaga Kependidikan Provinsi Sumatera Barat');

        return view('guest.display', compact(
            'todayGuests',
            'totalToday',
            'waitingToday',
            'activeToday',
            'finishedToday',
            'avgRating',
            'runningText',
            'institutionName'
        ));
    }
}
