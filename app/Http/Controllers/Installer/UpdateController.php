<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Repositories\Installer\InitRepository;
use App\Repositories\Installer\InstallRepository;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    protected $installRepository, $request, $path, $initRepository;
    protected $assets;

    public function __construct(
        InstallRepository $installRepository,
        Request $request, InitRepository $initRepository
    ) {
        $this->installRepository = $installRepository;
        $this->request = $request;
        $this->initRepository = $initRepository;
        $this->path = asset('public/installer');
    }

}
