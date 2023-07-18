<?php

namespace App\Http\Controllers\Backend\RealTimeData;

use App\Helpers\CoreApp\Traits\FirebaseDBConnection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealTimeDataFormatController extends Controller
{
    use FirebaseDBConnection;

    public function getData()
    {
        $db = $this->dbConnection();
        $docRef = $db->collection('adgari')->document('10');
        $snapshot = $docRef->snapshot();
    }
}
