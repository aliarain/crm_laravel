<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Hrm\Employee\AppoinmentRepository;
class AppointmentController extends Controller
{
    protected $appointRepo;
    public function __construct(AppoinmentRepository $appointRepo)
    {
        $this->appointRepo = $appointRepo;
    }

    public function index(Request $request)
    {
        return $this->appointRepo->getAllAppoinment($request);
    }
    public function getDetails(Request $request)
    {
        return $this->appointRepo->getDetails($request);
    }

    public function store(Request $request)
    {
        return $this->appointRepo->store($request);
    }
    public function update(Request $request)
    {
        return $this->appointRepo->update($request);
    }
    public function appoinmentChangeStatus(Request $request)
    {
        return $this->appointRepo->appoinmentChangeStatus($request);
    }
    public function delete(Request $request)
    {
        return $this->appointRepo->deleteAppoinment($request);
    }
}
