<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Http\Controllers\Controller;
use App\Repositories\Api\V1\CrmAppRepository;
use Illuminate\Http\Request;

class CrmApiController extends Controller
{
    use ApiReturnFormatTrait;

    protected $appSettings;

    public function __construct(CrmAppRepository $appSettingsRepository)
    {
        $this->appSettings = $appSettingsRepository;
    }

    public function AppDashboardScreen()
    {
        return $this->appSettings->GetAppDashboardScreen();
    }

    public function UsersList($type, $id)
    {
        return $this->appSettings->GetUsersList($type, $id);
    }

    public function AppHomeScreen(Request $request)
    {
        return $this->appSettings->GetAppHomeScreen($request);
    }

    /* ************************************* start projects ************************************** */
/**
 * Display the app projects screen.
 *
 * @return View
 */
    public function AppProjectsScreen()
    {
        return $this->appSettings->GetAppProjectsScreen();
    }

/**
 * Get a list of app projects.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function AppProjectsList(Request $request)
    {
        return $this->appSettings->GetAppProjectsList($request);
    }

/**
 * Get a list of app projects.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function GetAppProjectsStatusList(Request $request)
    {
        return $this->appSettings->GetAppProjectsStatusList($request);
    }

/**
 * Get details for a specific app project.
 *
 * @param  int  $id
 * @return JsonResponse
 */
    public function AppProjectsDetails($id)
    {
        return $this->appSettings->AppProjectsDetails($id);
    }

/**
 * Delete a specific app project.
 *
 * @param  int  $id
 * @return JsonResponse
 */
    public function AppProjectsDelete($id)
    {
        return $this->appSettings->AppProjectsDelete($id);
    }

/**
 * Store a new app project.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function AppProjectsStore(Request $request)
    {
        return $this->appSettings->AppProjectsStore($request);
    }

/**
 * Update an existing app project.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function AppProjectsUpdate(Request $request)
    {
        return $this->appSettings->AppProjectsUpdate($request);
    }

/**
 * Upload a file related to an app project.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function AppProjectsFileUpload(Request $request)
    {
        return $this->appSettings->AppProjectsFileUpload($request);
    }

/**
 * Change the status of an app project.
 *
 * @param  Request  $request
 * @return JsonResponse
 */
    public function AppTProjectsChangeStatus(Request $request)
    {
        return $this->appSettings->AppTProjectsChangeStatus($request);
    }

    /* ************************************* end projects ************************************** */

    public function AppClientsScreen()
    {
        return $this->appSettings->GetAppClientsScreen();
    }

    /******************** strat employee ********************/

    public function AppEmployeesScreen()
    {
        return $this->appSettings->GetAppEmployeesScreen();
    }

    public function AppEmployeeList(Request $request)
    {
        return $this->appSettings->GetAppEmployeeList($request);
    }
    public function AppEmployeeDetails(Request $request)
    {
        return $this->appSettings->AppEmployeeDetails($request);
    }
    public function AppEmployeeDelete($id)
    {
        return $this->appSettings->AppEmployeeDelete($id);
    }
    public function AppEmployeeStore(Request $request)
    {
        return $this->appSettings->AppEmployeeStore($request);
    }
    public function AppEmployeeUpdate(Request $request)
    {
        return $this->appSettings->AppEmployeeUpdate($request);
    }

    /******************** end employee ********************/

    public function AppSalesScreen()
    {
        return $this->appSettings->GetAppSalesScreen();
    }

    public function AppStockScreen()
    {
        return $this->appSettings->GetAppStockScreen();

    }
    public function AppPurchaseProducts()
    {
        return $this->appSettings->AppPurchaseProducts();

    }

    public function AppTaskScreen(Request $request)
    {
        return $this->appSettings->GetAppTaskScreen($request);
    }
    public function AppTaskChangeStatus(Request $request)
    {
        return $this->appSettings->AppTaskChangeStatus($request);
    }
    public function AppTaskDelete(Request $request)
    {
        return $this->appSettings->AppTaskDelete($request);
    }
    public function AppTaskList(Request $request)
    {
        return $this->appSettings->AppTaskList($request);
    }
    public function AppTaskCreate(Request $request)
    {
        return $this->appSettings->AppTaskCreate($request);
    }
    public function AppTaskDetails(Request $request, $task_id)
    {
        return $this->appSettings->AppTaskDetails($request, $task_id);
    }
    public function AppTaskStore(Request $request)
    {
        return $this->appSettings->AppTaskStore($request);
    }
    public function AppTaskUpdate(Request $request)
    {
        return $this->appSettings->AppTaskUpdate($request);
    }
    public function AppTaskStoreComment(Request $request)
    {
        return $this->appSettings->AppTaskStoreComment($request);
    }
    public function AppTaskDeleteComment(Request $request, $id)
    {
        return $this->appSettings->AppTaskDeleteComment($id);
    }
    public function AppTaskUpdateComment(Request $request)
    {
        return $this->appSettings->AppTaskUpdateComment($request);
    }
    public function AppTaskLikeFeedback(Request $request)
    {
        return $this->appSettings->AppTaskLikeFeedback($request);
    }

    public function AppIncomeScreen()
    {
        return $this->appSettings->GetAppIncomeScreen();
    }
    public function AppIncomeCreate()
    {
        return $this->appSettings->AppIncomeCreate();
    }
    public function AppIncomeList()
    {
        return $this->appSettings->GetAppIncomeList();
    }
    public function AppIncomeAdd(Request $request)
    {
        return $this->appSettings->AppIncomeAdd($request);
    }
    public function AppAccountsScreen()
    {
        return $this->appSettings->GetAppAccountsScreen();
    }
    public function AppClientsList(Request $request)
    {
        return $this->appSettings->GetAppClientsList($request);
    }
    public function AppClientsDetails(Request $request)
    {
        return $this->appSettings->AppClientsDetails($request);
    }
    public function AppClientsDelete($id)
    {
        return $this->appSettings->AppClientsDelete($id);
    }
    public function AppClientsProjects(Request $request, $client_id)
    {
        return $this->appSettings->AppClientsProjects($request, $client_id);
    }
    public function AppClientsStore(Request $request)
    {
        return $this->appSettings->AppClientsStore($request);
    }
    public function AppClientsUpdate(Request $request)
    {
        return $this->appSettings->AppClientsUpdate($request);
    }

    /* -------------------------------- stock category -------------------------------- */
    public function AppStockCategories(Request $request)
    {
        return $this->appSettings->GetStockCategories($request);
    }
    public function AppStockBrands(Request $request)
    {
        return $this->appSettings->GetAppStockBrands($request);
    }
    public function AppStockProducts(Request $request)
    {
        return $this->appSettings->GetAppStockProducts($request);
    }
}
