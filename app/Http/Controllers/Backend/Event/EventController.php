<?php

namespace App\Http\Controllers\Backend\Event;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\HolidayRepository;
use App\Http\Resources\Hrm\HolidayCollection;

class EventController extends Controller
{

    protected $holiday;

    public function __construct(HolidayRepository $holiday)
    {
        $this->holiday = $holiday;
    }

    public function index(Request $request)
    {
        $events= $this->holiday->appScreen($request);
        return new HolidayCollection($events);
    }
}
