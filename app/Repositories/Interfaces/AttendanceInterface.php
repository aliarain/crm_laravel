<?php

namespace App\Repositories\Interfaces;

interface AttendanceInterface
{
    public function getAll();

    public function index();

    public function store($request);

    public function dataTable($request, $id = null);
}
