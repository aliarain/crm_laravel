<?php

namespace App\Http\Controllers\Backend\Database;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Models\Database\DatabaseBackup;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    use ApiReturnFormatTrait;

    public function export()
    {
        try {
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $db_name = env('DB_DATABASE');
            $name = 'public/db/export_' . time() . '_' . date('y-m-d') . '.sql';

            $db = new DatabaseBackup();
            $db->path = $name;
            $db->save();

            exec("mysqldump -u$username -p$password $db_name > $name");

            Toastr::success('Database backup file saved successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $exception) {
            Toastr::error($exception->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $check = DatabaseBackup::find($id);
        $upload_path = $check->path;
        if (file_exists($upload_path)) {
            unlink($check->path);
            DatabaseBackup::where('id', $id)->delete();
        } else {
            DatabaseBackup::where('id', $id)->delete();
        }
        Toastr::success('Database deleted successfully', 'Success');
        return redirect()->back();
    }

    public function download($id)
    {
        $check = DatabaseBackup::find($id);
        $filepath = public_path('database/' . $check->path);
        if (file_exists($filepath)) {
            return response()->download($filepath);
        }
    }
}
