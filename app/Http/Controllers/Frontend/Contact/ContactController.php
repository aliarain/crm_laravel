<?php

namespace App\Http\Controllers\Frontend\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Repositories\Hrm\Contact\ContactRepository;

class ContactController extends Controller
{
    
    protected $contactRepo;

    public function __construct(ContactRepository $contactRepo)
    {
        $this->contactRepo = $contactRepo;
    }

    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->contactRepo->table($request);
            }
            $data['fields'] = $this->contactRepo->fields();
            $data['title'] = _trans('common.Languages');
            $data['url_id']    = 'contact_table_url';
            $data['class']     = 'table_class';

            $data['title'] = _trans('common.Contact');
            return view('backend.contact.index',compact('data'));
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        try {
            return $this->contactRepo->datatable($request);
        } catch (\Throwable $th) {
            Toastr::error(_trans('response.Something went wrong!'), 'Error');
            return redirect()->back();
        }
    }

     
}
