<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportXls()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function exportCsv()
    {
        //create csv with all users (name, email, created_at)
        return Excel::download(new UsersExport, 'users.csv', \Maatwebsite\Excel\Excel::CSV);
    }
}
