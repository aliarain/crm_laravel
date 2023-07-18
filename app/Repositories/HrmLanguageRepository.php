<?php

namespace App\Repositories;

use App\Models\Settings\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Settings\HrmLanguage;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;
use App\Repositories\Settings\CompanyConfigRepository;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class HrmLanguageRepository.
 */
class HrmLanguageRepository extends BaseRepository
{
    use RelationshipTrait, ApiReturnFormatTrait;
    /**
     * @return string
     *  Return the model
     */
    // protected $company_config;
    public function model()
    {
        return Language::class;
    }

    public function dataTable()
    {
        $hrm_languages = $this->model->get();
        return datatables()->of($hrm_languages)

            ->addColumn('action', function ($data) {

                $action_button = '';
                $setup = _trans('settings.Setup');
                $makeDefault = _trans('settings.Make Default');
                $makeActive = _trans('settings.Change Status');
                $delete = _trans('common.Delete');

                $action_button .= '<a href="' . route('language.setup', $data->id) . '" class="dropdown-item"> ' . $setup . '</a>';
                if ($data->is_default == 0) {
                    if ($data->status == 1) {
                        $action_button .= '<a href="' . route('language.makeDefault', $data->id) . '" class="dropdown-item">' . $makeDefault . '</a>';
                    }
                    // $action_button .= actionButton($delete, '__globalDelete(' . $data->id . ',`admin/settings/language-setup/delete/`)', 'delete');
                }
                if ($data->is_default == 0) {
                    $action_button .= '<a href="' . route('language.makeActive', $data->id) . '" class="dropdown-item">' . $makeActive . '</a>';
                }

                $button = '  <div class="align-self-center">
                <div class="dropdown dropdown-action" data-bs-toggle="tooltip" data-bs-placement="right"
                    data-bs-title="Action">
                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        ' . $action_button . ' 
                    </ul>
                </div>
            </div>';
                return $button;
            })
            ->addColumn('code', function ($data) {
                return $data->code;
            })
            ->addColumn('name', function ($data) {
                $default = $data->is_default == 1 ? '<span class="badge badge-success">Default</span>' : '';
                return $data->name . ' ' . $default;
            })
            ->addColumn('native', function ($data) {
                return $data->native;
            })
            ->addColumn('rtl', function ($data) {
                return $data->IsRtl;
            })
            ->addColumn('status', function ($data) {
                return $data->IsActive;
            })

            ->rawColumns(array('action', 'code', 'name', 'native', 'rtl', 'status'))
            ->make(true);
    }

    public function pluckData($data)
    {
        return $this->model->pluck($data);
    }
    public function makeDefault($id)
    {
        try {

            DB::beginTransaction();

            $this->model->wherein('is_default', [1])->update(array('is_default' => 0));

            $language = $this->model->where('id', $id)->first();
            $language->is_default = 1;
            $language->save();


            CompanyConfigRepository::setupConfig('lang', $language->code);

            if (auth()->user()->role_id == 1) {
                putEnvConfigration('APP_LOCAL', $language->code);
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
    public function makeActive($id)
    {
        try {

            DB::beginTransaction();

            $language = $this->model->where('id', $id)->first();
            $language->status = $language->status == 1 ? 0 : 1;
            $language->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function deleteLang($id)
    {
        try {

            DB::beginTransaction();

            $check_default =  $this->model->find($id);
            if ($check_default->is_default == 1) {
                return false;
            }
            $folder = @resource_path('lang/' . @$check_default->language->code . '/');
            if (file_exists($folder)) {
                $this->deleteDir($folder);
            }
            $check_default->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }


    // new functions

    function fields()
    {
        return [
            _trans('common.ID'),
            _trans('common.Name'),
            _trans('common.Native'),
            _trans('common.Code'),
            _trans('common.RTL'),
            _trans('common.Status'),
            _trans('common.Action')

        ];
    }

    function table($request)
    {
        
        $data =  $this->model->query();
        $where = array();
        if ($request->search) {
            $where[] = ['name', 'like', '%' . $request->search . '%'];
        }
        $data = $data
            ->where($where)
            ->orderBy('is_default', 'desc')
            ->paginate($request->limit ?? 2);
        return [
            'data' => $data->map(function ($data) {
                $action_button = '';
                $action_button = '';
                $setup = _trans('settings.Setup');
                $delete = _trans('common.Delete');

                if (hasPermission('language_edit')) {
                    $action_button .= actionButton(_trans('common.Edit'), 'mainModalOpen(`' . route('language.edit', $data->id) . '`)', 'modal');
                }

                if (hasPermission('setup_language')) { 
                    $action_button .= '<a href="' . route('language.setup', $data->id) . '" class="dropdown-item">  <span class="icon mr-8"><i class="fa-solid fa-gear"></i></span>' . $setup . '</a>';
                }

                if (hasPermission('make_default') && @$data->is_default == 0 && @$data->status == 1) {
                    $action_button .= actionButton(_trans('settings.Make Default'), '__deleteAlert(`' . route('language.makeDefault', $data->id) . '`)', 'delete');
                }
                if ($data->is_default == 0) {
                    $action_button .= actionButton(_trans('settings.Change Status'), '__deleteAlert(`' . route('language.makeActive', $data->id) . '`)', 'delete');
                }

                if ($data->is_default == 0) {
                    $action_button .= actionButton(_trans('common.Delete'), '__deleteAlert(`' . route('language.deleteLang', $data->id) . '`)', 'delete');
                }

                $button = ' <div class="dropdown dropdown-action">
                                    <button type="button" class="btn-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    ' . $action_button . '
                                    </ul>
                                </div>';
                $default = $data->is_default == 1 ? '<span class="badge badge-success">Default</span>' : '';

                return [
                    'id' => $data->id,
                    'code' => $data->code,
                    'name' => $data->name . ' ' . $default,
                    'native' => $data->native,
                    'rtl' => $data->IsRtl,
                    'status' => $data->IsActive,
                    'action'   => $button
                ];
            }),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
                'pagination_html' =>  $data->links('backend.pagination.custom')->toHtml(),
            ],
        ];
    }

    function createAttributes()
    {
        return [
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name')
            ],
            'native' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'native',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('settings.Native')
            ],
            'code' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'code',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('settings.Code')
            ],
            'rtl' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'rtl',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('settings.RTL'),
                'options' => [
                    [
                        'text' => _trans('common.Yes'),
                        'value'  => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('common.No'),
                        'value'  => 0,
                        'active' => false,
                    ]
                ]
            ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => true,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 0,
                        'active' => false,
                    ]
                ]
            ]

        ];
    }
    function editAttributes($language)
    {
        return [
            'name' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'name',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Name'),
                'value' => @$language->name,
            ],
            'native' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'native',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('settings.Native'),
                'value' => @$language->native
            ],
            'code' => [
                'field' => 'input',
                'type' => 'text',
                'required' => true,
                'id'    => 'code',
                'class' => 'form-control ot-form-control ot_input',
                'col'   => 'col-md-12 form-group mb-3',
                'label' => _trans('settings.Code'),
                'value' => @$language->code
            ],
            'rtl' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'rtl',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.RTL'),
                'options' => [
                    [
                        'text' => _trans('common.Yes'),
                        'value'  => 1,
                        'active' => $language->rtl == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('common.No'),
                        'value'  => 0,
                        'active' =>  $language->rtl == 0 ? true : false,
                    ]
                ]
                    ],
            'status' => [
                'field' => 'select',
                'type' => 'select',
                'required' => true,
                'id'    => 'status',
                'class' => 'form-select select2-input ot_input mb-3 modal_select2',
                'col' => 'col-md-12 form-group mb-3',
                'label' => _trans('common.Status'),
                'options' => [
                    [
                        'text' => _trans('payroll.Active'),
                        'value'  => 1,
                        'active' => $language->status == 1 ? true : false,
                    ],
                    [
                        'text' => _trans('payroll.Inactive'),
                        'value'  => 0,
                        'active' =>  $language->status == 0 ? true : false,
                    ]
                ]
            ]

        ];
    }

    function newStore($request)
    {
        try {
            if (blank($this->model->where('name', $request->name)->first())) {
                $language = new $this->model;
                $language->name = $request->name;
                $language->native = $request->native;
                $language->rtl = $request->rtl;
                $language->status = $request->status;
                $language->code = $request->code;
                $language->save();
                return $this->responseWithSuccess(_trans('message.Language store successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
    function newUpdate($request, $id)
    {
        try {
            if (blank($this->model->where('name', $request->name)->where('id', '!=', $id)->first())) {
                $language = $this->model->find($id);
                if ($language->is_default == 1 && $request->status == 0) {
                    return $this->responseWithError(_trans('message.Default language can not be inactive'), [], 400);
                }
                $language->name = $request->name;
                $language->native = $request->native;
                $language->rtl = $request->rtl;
                $language->status = $request->status;
                $language->code = $request->code;
                $language->save();
                return $this->responseWithSuccess(_trans('message.Language update successfully.'), 200);
            } else {
                return $this->responseWithError(_trans('message.Data already exists'), [], 400);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), [], 400);
        }
    }
}
