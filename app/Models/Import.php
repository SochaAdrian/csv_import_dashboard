<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $fillable = ['file_name', 'status', 'error_message', 'rows'];

    public function logs()
    {
        return $this->hasMany(ImportLog::class, 'import_id', 'id');
    }

    public function addLog($rowNumber, $errorMessage)
    {
        $this->logs()->create([
            'row_number' => $rowNumber,
            'error_message' => $errorMessage
        ]);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'imported_with_import_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
