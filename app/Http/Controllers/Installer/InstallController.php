<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Installer\InstallRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class InstallController extends Controller
{
    protected $installRepository, $request, $init, $path;

    public function __construct(InstallRepository $installRepository, Request $request)
    {
        $this->installRepository = $installRepository;
        $this->request = $request;
        $this->path = asset('public/installer');
    }

    public function updateEnvValue($key, $value)
    {
        $envFilePath = base_path('.env');

        // Get the current contents of the .env file
        $envFileContents = file_get_contents($envFilePath);

        // Replace the value of the specified key with the new value
        $newEnvFileContents = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $value, $envFileContents);

        // Write the updated contents back to the .env file
        file_put_contents($envFilePath, $newEnvFileContents);

    }
    public function CheckEnvironment()
    {

        $data['title'] = _trans('installer.Check Your Environment For CRM Installation');
        $data['Server-Requirements'] = _trans('installer.Server Requirements');
        $data['Folder-Requirements'] = _trans('installer.Folder Requirements');
        $data['notify'] = _trans('installer.Please make sure that all the requirements are met before proceeding to the next step.');
        $data['success'] = _trans('installer.It looks like everything meets the requirements, Please click the button below to continue.');
        $data['asset_path'] = $this->path;
        $data['button_text'] = _trans('installer.Continue');

        // Set a session value
        $this->installRepository->checkStage('CheckEnvironment');

        $checks = $this->installRepository->getPreRequisite();
        $server_checks = $checks['server'];
        $folder_checks = $checks['folder'];
        $verifier = $checks['verifier'];
        $has_false = in_array(false, $checks);

        $this->updateEnvValue('APP_ENV', 'production');
        $this->updateEnvValue('APP_DEBUG', 'false');

        $name = env('APP_NAME');

        return view('installer.install.preRequisite', compact('server_checks', 'folder_checks', 'name', 'verifier', 'has_false', 'data'));
    }

    public function license()
    {

        $data['title'] = _trans('installer.License Verification');
        $data['Access-Code'] = _trans('installer.Access Code');
        $data['info'] = _trans('installer.Please enter your access code to verify your license.');
        $data['Envato-Email'] = _trans('installer.Envato Email');
        $data['Installed-Domain'] = _trans('installer.Installed Domain');
        $data['button_text'] = _trans('installer.Continue');

        $data['asset_path'] = $this->path;
        // Set a session value
        $this->installRepository->checkStage('LicenseVerification');

        $checks = $this->installRepository->getPreRequisite();
        if (in_array(false, $checks)) {
            return redirect()->route('service.checkEnvironment')->with(['message' => _trans('installer.requirement_failed'), 'status' => 'error']);
        }

        $reinstall = $this->installRepository->checkReinstall();
        return view('installer.install.license', compact('reinstall', 'data'));
    }

    public function post_license(Request $request)
    {
        // return $request;
        try {
            $response = $this->installRepository->validateLicense($request->all());
            if ($response && gv($response, 'goto')) {
                $message = __('We can not verify your credentials, Please wait');
                $goto = $response['goto'];
            } else {
                session()->flash('license', 'verified');
                $goto = route('service.database');
                $message = _trans('installer.Valid License for initial installation');
            }
            return response()->json(['message' => $message, 'goto' => $goto]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage(), 'status' => 'error']);
        }
    }

    public function database()
    {

        $data['asset_path'] = $this->path;
        $data['title'] = _trans('installer.Check Database Setup and Connection');
        $data['button_text'] = _trans('installer.Continue');
        $data['DB HOST'] = _trans('installer.DB HOST');
        $data['DB PORT'] = _trans('installer.DB PORT');
        $data['DB DATABASE'] = _trans('installer.DB DATABASE');
        $data['DB USERNAME'] = _trans('installer.DB USERNAME');
        $data['DB PASSWORD'] = _trans('installer.DB PASSWORD');
        $data['Force Delete Previous Table'] = _trans('installer.Force Delete Previous Table');
        $data['button_text'] = _trans('installer.Continue');

        // Set a session value
        session(['DatabaseSetup' => true]);
        Storage::disk('local')->put('.DatabaseSetup', 'DatabaseSetup');

        return view('installer.install.database', compact('data'));
    }

    public function post_database(Request $request)
    {

        try {
            $this->installRepository->validateDatabase($request->all());
            return response()->json(['message' => _trans('installer.connection_established'), 'goto' => route('service.user')]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }

    }

    public function done()
    {

        $data['asset_path'] = $this->path;
        $data['title'] = _trans('installer.Complete Installation and Configuration');
        $data['info'] = _trans('installer.Congratulations! You successfully installed the application. Please login to your account to start using the application.');

        $data['asset_path'] = $this->path;

        $user = User::find(2);
        if ($user) {
            $data['email'] = $user->email;
            $data['password'] = session('password') ?? '';
        } else {

            $data['email'] = session('email') ?? '';
            $data['password'] = session('password') ?? '';
        }

        // Set a session value
        session(['Complete' => true]);
        Storage::disk('local')->put('.Complete', 'Complete');
        return view('installer.install.done', compact('data'));

    }

    public function ManageAddOnsValidation(ModuleInstallRequest $request)
    {
        $response = $this->installRepository->installModule($request->all());
        if ($response) {
            if ($request->wantsJson()) {
                return response()->json(['message' => _trans('installer.module_verify'), 'reload' => true]);
            }
            Toastr::success(_trans('installer.module_verify'), 'Success');
        }
        return back();

    }

    public function uninstall()
    {
        $response = $this->installRepository->uninstall($this->request->all());
        $message = 'Uninstall by script author successfully';
        info($message);
        return response()->json(['message' => $message, 'response' => $response]);
    }

    public function installTheme(ThemeInstallRequest $request)
    {
        $this->installRepository->installTheme($request->all());
        $message = _trans('installer.theme_verify');
        if ($request->ajax()) {
            return response()->json(['message' => $message, 'reload' => true]);
        }

        Toastr::success($message);
        return redirect()->back();

    }

    public function reinstall(Request $request, $key)
    {

        if ($key == "sure") {

            $list = [
                '30626608',
                '.access_code',
                '.access_log',
                '.account_email',
                '.app_installed',
                '.install_count',
                '.logout',
            ];

            foreach ($list as $key => $value) {
                if (Storage::disk('local')->exists($value)) {
                    Storage::disk('local')->delete($value);
                }
            }

            return redirect('/');

        } else {
            abort(404);
        }
    }

    public function index()
    {
        $data['title'] = _trans('installer.Welcome To Installation');
        $data['short_note'] = _trans('installer.Welcome to One Stop CRM, to complete the installation, please proceed to the next step!');
        $data['button_text'] = _trans('installer.Get Started');
        $data['asset_path'] = $this->path;

        // check stage & Set a session value
        $this->installRepository->checkStage('WelcomeNote');

        session()->forget('CheckEnvironment');
        session()->forget('LicenseVerification');
        session()->forget('DatabaseSetup');
        session()->forget('AdminSetup');
        session()->forget('Complete');

        return view('installer.install.welcome', compact('data'));
    }

    public function AdminSetup()
    {

        $data['title'] = _trans('installer.Admin Setup');
        $data['asset_path'] = url('public/installer');

        $this->installRepository->checkStage('AdminSetup');

        // Set a session value

        return view('installer.install.user', compact('data'));
    }

    public function DbSeed()
    {
        try {
            $this->updateEnvValue('APP_ENV', 'production');
            $this->updateEnvValue('APP_DEBUG', 'false');
            $this->updateEnvValue('APP_CRM', 'false');
            ini_set('max_execution_time', -1);

            // Start a transaction
            DB::beginTransaction();

            // $sql = file_get_contents('public/installer/db/crm.sql');
            $sql = file_get_contents('public/installer/db/crm.sql');
            DB::unprepared($sql);

            // Commit the transaction
            DB::commit();
        } catch (\Throwable $e) {
            // Rollback the transaction on error
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage(),
                'goto' => route('service.import_sql'),
                'error' => $e->getMessage(),
                'step' => 'AdminSetup',
            ]);
        }

    }

    public function post_user(Request $request)
    {

        if ($request->all() == null) {
            $request['email'] = 'admin@onesttech.com';
            $request['password'] = '12345678';
        }

        $request->session()->forget('email');
        $request->session()->forget('password');

        session(['email' => $request->email]);
        session(['password' => $request->password]);

        try {

            return response()->json([
                'message' => 'Admin Setup Successfully Created',
                'goto' => route('service.import_sql'),
                'error' => '',
                'step' => 'AdminSetup',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'goto' => route('service.user'),
                'error' => $e->getMessage(),
                'step' => 'AdminSetup',
            ]);

        }

    }

    public function import_sql(Request $request)
    {
        $data['title'] = _trans('installer.Admin Setup & Import SQL');
        $data['asset_path'] = url('public/installer');
        $data['sql'] = 'public/installer/db/crm.sql';
        $data['button_text'] = _trans('installer.Next');
        return view('installer.install.import_sql', compact('data'));

    }

    public function updateDB()
    {
        if (Schema::hasTable('users')) {
            $user = DB::table('users')->find(2);

            if ($user != "") {
                DB::table('users')
                    ->where('id', 2)
                    ->update([
                        'name' => 'Admin',
                        'email' => session('email'),
                        'password' => Hash::make(session('password')),
                        'role_id' => 2,
                        'is_admin' => 1,
                        'is_email_verified' => 1,
                        'email_verified_at' => now(),
                    ]);
                return true;

            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function import_sql_post()
    {

        if (session('email') && session('password')) {
            if (!$this->updateDB()) {

                return redirect()->back()->with([
                    'error' => 'Import SQL file properly!',
                    'step' => 'AdminSetup',
                ]);
    
            }
        } else {
            return redirect()->back()->with([
                'message' => 'Please re-enter!',
                'goto' => route('service.user'),
                'error' => 'Re-enter your information',
                'step' => 'AdminSetup',
            ]);

        }

        $this->installRepository->checkStage('WelcomeNote');
        $this->installRepository->checkStage('CheckEnvironment');
        $this->installRepository->checkStage('LicenseVerification');
        $this->installRepository->checkStage('DatabaseSetup');
        $this->installRepository->checkStage('AdminSetup');
        $this->installRepository->checkStage('Complete');

        $data['title'] = _trans('installer.Complete Installation and Configuration');
        $data['info'] = _trans('installer.Congratulations! You successfully installed the application. Please login to your account to start using the application.');

        $data['asset_path'] = $this->path;
        $data['email'] = session('email') ?? '';
        $data['password'] = session('password') ?? '';

        return redirect()->route('service.done')->with('data', $data);

    }

}
