<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    //Since logs are not really buisness data we should store them in separate way to buisness data to avoid performance issues
    //In this particular app it doesn't really matter we could store them in the same database but for the sake of the task we will store them in separate database
    protected $connection = 'sqlite_import_logs';
    protected $fillable = ['import_id', 'row_number', 'error_message', 'value'];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
