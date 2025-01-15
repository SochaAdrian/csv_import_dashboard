<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Jobs\ProcessUserImport;
use App\Models\Import;
use App\Models\ImportLog;
use Inertia\Inertia;

class ImportController extends Controller
{
    public function index()
    {
        return Inertia::render('CSV/Form');
    }

    public function store(ImportRequest $request)
    {
        //Save and read how many rows file has
        $file = $request->file('file');
        $filePath = $file->getRealPath();
        $fileContent = fopen($filePath, 'r');
        $rowCount = 0;

        //If more than 1000 return that file contains too many rows
        while (($row = fgetcsv($fileContent)) !== false) {
            $rowCount++;
            if ($rowCount > 1000) {
                fclose($fileContent);
                return response()->json([
                    'message' => 'The file contains more than 1000 rows, which is not allowed.',
                ], 422);
            }
        }

        //close store and dispatch import job
        fclose($fileContent);
        $storedPath = $file->store('imports');
        $import = Import::create([
            'file_name' => $storedPath,
            //-1 because of the header row
            'rows' => $rowCount - 1,
            'status' => 'pending',
        ]);
        ProcessUserImport::dispatch($import);
        return redirect()->back();
    }

    public function show(Import $import)
    {
        return $import;
    }

    public function update(ImportRequest $request, Import $import)
    {
        $import->update($request->validated());

        return $import;
    }

    public function destroy(Import $import)
    {
        $import->delete();

        return response()->json();
    }

    public function dashboard()
    {
        return Inertia::render('CSV/Dashboard', ['imports' => Import::with(['users', 'logs'])->get()]);
    }

    public function logs()
    {
        return Inertia::render('CSV/Logs', ['logs' => ImportLog::all()]);
    }
}
