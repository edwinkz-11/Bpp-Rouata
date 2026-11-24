<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use LogsActivity;

    public const TYPES = ['pertanyaan' => 'Pertanyaan', 'saran' => 'Saran', 'pengaduan' => 'Pengaduan', 'permohonan_informasi' => 'Permohonan Informasi'];

    public const STATUSES = ['new' => 'Baru', 'read' => 'Sudah dibaca', 'resolved' => 'Selesai ditindaklanjuti'];

    protected $fillable = ['name', 'email', 'phone', 'type', 'subject', 'message', 'status'];

    public static function unreadCount(): int
    {
        return static::where('status', 'new')->count();
    }
}
