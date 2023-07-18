<?php

namespace App\Repositories\Interfaces;

interface UserInterface
{
    public function getAll();

    public function getById($id);

    public function save($attributes);

    public function update($attributes);

    public function data_table($attributes);

    public function delete($id);
}
