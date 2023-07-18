<?php

namespace App\Services\Hrm;

use App\Services\Core\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\RelationCheck;
use Brian2694\Toastr\Facades\Toastr;

class DeleteService extends BaseService{

    use RelationCheck;

    public static function deleteData($table_name,$column_name, $id)
    {
        try {
            $table_data = DB::table($table_name)->delete($id);
            Toastr::success('Operation Done Successfully','Success');
            return redirect()->back();
        } catch(\Illuminate\Database\QueryException $ex){
            $db_name = env('DB_DATABASE', null);
            $table_list = DB::select("SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE COLUMN_NAME ='$column_name'
                AND TABLE_SCHEMA='$db_name'");
            $tables = '';

            foreach ($table_list as $row) {

                $data_test = DB::table($row->TABLE_NAME)->select('*')->where($column_name, $id)->first();

                if($data_test != ""){

                    $name = str_replace('sm_', '', $row->TABLE_NAME);
                    $name = str_replace('_', ' ', $name);
                    $name = ucfirst($name);
                    $tables .= $name . ', ';

                }
            }

            // $msg = 'This data already used in '. $tables.' tables, Please remove those data first';
            $msg = 'This data is using elsewhere';
            Toastr::error($msg,'Used Data');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }
    public static function deleteDataApi($table_name,$column_name, $id)
    {
        try {
            $table_data = DB::table($table_name)->delete($id);
            return response()->json(['success' => true, 'message' => 'Operation Done Successfully']);
        } catch(\Illuminate\Database\QueryException $ex){
            $db_name = env('DB_DATABASE', null);
            $table_list = DB::select("SELECT TABLE_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE COLUMN_NAME ='$column_name'
                AND TABLE_SCHEMA='$db_name'");
            $tables = '';

            foreach ($table_list as $row) {

                $data_test = DB::table($row->TABLE_NAME)->select('*')->where($column_name, $id)->first();

                if($data_test != ""){

                    $name = str_replace('sm_', '', $row->TABLE_NAME);
                    $name = str_replace('_', ' ', $name);
                    $name = ucfirst($name);
                    $tables .= $name . ', ';

                }
            }

            // $msg = 'This data already used in '. $tables.' tables, Please remove those data first';
            $msg = 'This data is using elsewhere';
            return response()->json(['success' => false, 'message' => $msg]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
            
        }
    }
}
