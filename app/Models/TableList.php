<?php

namespace App\Models;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class TableList extends Model
{
    	public static function getTableList($colunm_name_id, $id)
	{
		try{
			$db_name = env('DB_DATABASE', null);
			$table_list = DB::select("SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE COLUMN_NAME ='$colunm_name_id'
				AND TABLE_SCHEMA='$db_name'");
			$tables = '';

			foreach ($table_list as $row) {

				$data_test = DB::table($row->TABLE_NAME)->select('*')->where($colunm_name_id, $id)->first();

				if($data_test != ""){

					$name = str_replace('sm_', '', $row->TABLE_NAME);
					$name = str_replace('_', ' ', $name);
					$name = ucfirst($name);
					$tables .= $name . ', ';

				}
			}

			return $tables;
		}catch(\Exception $e){
			$tables='Exception';
			return $tables;
		}
	}
    	public static function getTableFullName($colunm_name_id, $id)
	{
		try{
			$db_name = env('DB_DATABASE', null);
			$table_list = DB::select("SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE COLUMN_NAME ='$colunm_name_id'
				AND TABLE_SCHEMA='$db_name'");
			$tables = '';

			foreach ($table_list as $row) {

				$data_test = DB::table($row->TABLE_NAME)->select('*')->where($colunm_name_id, $id)->first();

				if($data_test != ""){

					$name = $row->TABLE_NAME;
			
					$tables .= $name . ', ';

				}
			}

			return $tables;
		}catch(\Exception $e){
			$tables='Exception';
			return $tables;
		}
	}
}
