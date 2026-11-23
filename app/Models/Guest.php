<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'visit_date',
        'check_in_time',
        'check_out_time',
        'name',
        'identity_number',
        'phone',
        'email',
        'institution',
        'position',
        'city_regency',
        'purpose',
        'department_id',
        'destination_person',
        'companion_count',
        'photo_path',
        'signature_path',
        'status',
        'officer_notes',
        'satisfaction_rating',
        'satisfaction_feedback',
        'rated_at',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'companion_count' => 'integer',
        'satisfaction_rating' => 'integer',
        'rated_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_path) {
            return null;
        }

        if (str_starts_with($this->photo_path, 'data:image')) {
            return $this->photo_path;
        }

        return Storage::url($this->photo_path);
    }

    public function getSignatureUrlAttribute(): ?string
    {
        if (!$this->signature_path) {
            return null;
        }

        if (str_starts_with($this->signature_path, 'data:image')) {
            return $this->signature_path;
        }

        return Storage::url($this->signature_path);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu',
            'sedang_bertemu' => 'Sedang Bertemu',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'bg-amber-100 text-amber-800 border-amber-300',
            'sedang_bertemu' => 'bg-blue-100 text-blue-800 border-blue-300',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public static function generateTicketNumber(): string
    {
        $today = Carbon::today()->format('Ymd');
        $countToday = self::whereDate('visit_date', Carbon::today())->count() + 1;
        return sprintf('BGTK-%s-%04d', $today, $countToday);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('visit_date', Carbon::today());
    }

    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('visit_date', Carbon::today()->month)
                     ->whereYear('visit_date', Carbon::today()->year);
    }
}
