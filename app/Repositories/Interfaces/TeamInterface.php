<?php

namespace App\Repositories\Interfaces;

interface TeamInterface
{
    public function index();

    public function dataTable($request, $id = null);

    public function store($request);

    public function show($id);

    public function update($request, $id);

    public function destroy($id);

}