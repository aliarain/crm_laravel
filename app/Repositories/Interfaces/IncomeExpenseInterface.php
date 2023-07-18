<?php

namespace App\Repositories\Interfaces;

interface IncomeExpenseInterface
{
    public function getAll();

    public function getById($id);

    public function create($attributes);

    public function update($attributes,$id);

    public function data_table($attributes);

    public function delete($id);
}
