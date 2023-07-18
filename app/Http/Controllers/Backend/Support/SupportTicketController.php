<?php

namespace App\Http\Controllers\Backend\Support;

use App\Http\Controllers\Controller;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Support\SupportTicketRepository;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    use RelationshipTrait;

    protected $support;

    public function __construct(SupportTicketRepository $support)
    {
        $this->support = $support;
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->support->store($request);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        return $this->support->show($id);
    }

    public function listView(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->support->ticketList($request);
    }
}
