<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesktopApplication extends Model
{
    use HasFactory;

    const WINDOWS_FILE_PATH = 'https://envato.froid.works/app/download/windows';
    const MAC_INTEL_FILE_PATH = 'https://envato.froid.works/app/download/macos-intel';
    const MAC_SILICON_FILE_PATH = 'https://envato.froid.works/app/download/macos-silicon';
    const LINUX_FILE_PATH = 'https://envato.froid.works/app/download/linux';

    protected $guarded = ['id'];
    protected $table = 'desktop_applications';

    public function getIsActiveAttribute()
    {
        return $this->windows_file_path || $this->mac_intel_file_path || $this->mac_silicon_file_path || $this->linux_file_path;
    }
}
