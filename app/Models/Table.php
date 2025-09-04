<?php

namespace App\Models;

use App\Helper\Files;
use App\Traits\HasBranch;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Facades\File as FileFacade;
use Illuminate\Support\Facades\Storage;
use App\Traits\GeneratesQrCode;
use App\Models\BaseModel;

class Table extends BaseModel
{

    use HasFactory;
    use HasBranch;
    use GeneratesQrCode;

    protected $guarded = ['id'];

    protected $casts = [
        'occupied_at' => 'datetime',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function activeOrder(): HasOne
    {
        return $this->hasOne(Order::class)->where('status', 'kot')->orderBy('id', 'desc');
    }

    public function qRCodeUrl(): Attribute
    {
        return Attribute::get(fn(): string => asset_url_local_s3('qrcodes/' . $this->getQrCodeFileName()));
    }

    public function generateQrCode()
    {
        $this->createQrCode(route('table_order', [$this->hash]), __('modules.table.table') . ' ' . str()->slug($this->table_code, '-', (auth()->user() ? auth()->user()->locale : 'en')));
    }

    public function getQrCodeFileName(): string
    {
        return 'qrcode-' . $this->branch_id . '-' . str()->slug($this->table_code, '-', (auth()->user() ? auth()->user()->locale : 'en')) . '.png';
    }

    public function getRestaurantId(): int
    {
        return $this->branch?->restaurant_id;
    }

    public function activeWaiterRequest(): HasOne
    {
        return $this->hasOne(WaiterRequest::class)->where('status', 'pending');
    }

    public function waiterRequests(): HasMany
    {
        return $this->hasMany(WaiterRequest::class);
    }

    /**
     * Mark table as occupied and set the occupied timestamp
     */
    public function markAsOccupied(): void
    {
        $this->update([
            'available_status' => 'running',
            'occupied_at' => now(),
        ]);
    }

    /**
     * Mark table as available and clear the occupied timestamp
     */
    public function markAsAvailable(): void
    {
        $this->update([
            'available_status' => 'available',
            'occupied_at' => null,
        ]);
    }

    /**
     * Get the duration the table has been occupied in minutes
     */
    public function getOccupiedDurationAttribute(): ?int
    {
        if (!$this->occupied_at || $this->available_status !== 'running') {
            return null;
        }

        return $this->occupied_at->diffInMinutes(now());
    }

    /**
     * Get formatted occupied duration as HH:MM
     */
    public function getFormattedOccupiedDurationAttribute(): ?string
    {
        $duration = $this->occupied_duration;
        
        if ($duration === null) {
            return null;
        }

        $hours = intval($duration / 60);
        $minutes = $duration % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
