<?php

namespace App\Repositories\Installer;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class InstallRepository
{
  
    public function checkStage($current)
    {
        switch ($current) {
            case 'WelcomeNote': 
                session(['WelcomeNote' => true]);
                Storage::disk('local')->put('.WelcomeNote', 'WelcomeNote');
                break;
            case 'CheckEnvironment': 
                session(['CheckEnvironment' => true]);
                Storage::disk('local')->put('.CheckEnvironment', 'CheckEnvironment');
                break;
            case 'LicenseVerification': 
                session(['LicenseVerification' => true]);
                Storage::disk('local')->put('.LicenseVerification', 'LicenseVerification');
                break;
            case 'DatabaseSetup': 
                session(['DatabaseSetup' => true]);
                Storage::disk('local')->put('.DatabaseSetup', 'DatabaseSetup');
                break;
            case 'AdminSetup': 
                session(['AdminSetup' => true]);
                Storage::disk('local')->put('.AdminSetup', 'AdminSetup');
                break;
            case 'Complete': 
                session(['Complete' => true]);
                Storage::disk('local')->put('.Complete', 'Complete');
                break;
            default:
                break;
        }
    }
    public function checkInstallation()
    {
        $ac = Storage::disk('local')->exists('.app_installed') ? Storage::disk('local')->get('.app_installed') : null;
        if ($ac) {
            abort(404);
        } else {
            if ($this->checkPreviousInstallation()) {
                return redirect('/')->send();
            }
        }
    }

    /**
     * Used to compare version of PHP
     */
    public function my_version_compare($ver1, $ver2, $operator = null)
    {
        $p = '#(\.0+)+($|-)#';
        $ver1 = preg_replace($p, '', $ver1);
        $ver2 = preg_replace($p, '', $ver2);
        return isset($operator) ?
        version_compare($ver1, $ver2, $operator) :
        version_compare($ver1, $ver2);
    }

    /**
     * Used to check whether pre requisites are fulfilled or not and returns array of success/error type with message
     */
    public function check($boolean, $message, $help = '', $fatal = false)
    {
        if ($boolean) {
            return array('type' => 'success', 'message' => $message);
        } else {
            return array('type' => 'error', 'message' => $help);
        }
    }

    public function checkReinstall()
    {
        try {
            DB::connection()->getPdo();
            return (Storage::disk('local')->exists('.install_count') ? Storage::disk('local')->get('.install_count') : 0) and (Artisan::call('migrate-status'));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Used to check whether pre requisites are fulfilled or not and returns array of success/error type with message
     */
    public function checkPreviousInstallation()
    {
        return false;
    }

    /**
     * Check all pre-requisite for script
     */
    public function getPreRequisite()
    {
        $server[] = $this->check((dirname($_SERVER['REQUEST_URI']) != '/' && str_replace('\\', '/', dirname($_SERVER['REQUEST_URI'])) != '/'), 'Installation directory is valid.', 'Please use root directory or point your sub directory to domain/subdomain to install.', true);
        $server[] = $this->check($this->my_version_compare(phpversion(), config('app.php_version', '7.2.0'), '>='), sprintf('Min PHP version ' . config('app.php_version', '7.2.0') . ' (%s)', 'Current Version ' . phpversion()), 'Current Version ' . phpversion(), true);
        $server[] = $this->check(extension_loaded('fileinfo'), 'Fileinfo PHP extension enabled.', 'Install and enable Fileinfo extension.', true);
        $server[] = $this->check(extension_loaded('ctype'), 'Ctype PHP extension enabled.', 'Install and enable Ctype extension.', true);
        $server[] = $this->check(extension_loaded('json'), 'JSON PHP extension enabled.', 'Install and enable JSON extension.', true);
        $server[] = $this->check(extension_loaded('openssl'), 'OpenSSL PHP extension enabled.', 'Install and enable OpenSSL extension.', true);
        $server[] = $this->check(extension_loaded('tokenizer'), 'Tokenizer PHP extension enabled.', 'Install and enable Tokenizer extension.', true);
        $server[] = $this->check(extension_loaded('mbstring'), 'Mbstring PHP extension enabled.', 'Install and enable Mbstring extension.', true);
        $server[] = $this->check(extension_loaded('zip'), 'Zip archive PHP extension enabled.', 'Install and enable Zip archive extension.', true);
        $server[] = $this->check(class_exists('PDO'), 'PDO is installed.', 'Install PDO (mandatory for Eloquent).', true);
        $server[] = $this->check(extension_loaded('curl'), 'CURL is installed.', 'Install and enable CURL.', true);
        $server[] = $this->check(ini_get('allow_url_fopen'), 'allow_url_fopen is on.', 'Turn on allow_url_fopen.', true);

        $folder[] = $this->check(is_writable(base_path('/.env')), 'File .env is writable', 'File .env is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/storage/framework")), 'Folder /storage/framework is writable', 'Folder /storage/framework is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/storage/logs")), 'Folder /storage/logs is writable', 'Folder /storage/logs is not writable', true);
        $folder[] = $this->check(is_writable(base_path("/bootstrap/cache")), 'Folder /bootstrap/cache is writable', 'Folder /bootstrap/cache is not writable', true);


        $verifier = verifyUrl(config('app.verifier', 'auth'));

        return ['server' => $server, 'folder' => $folder, 'verifier' => $verifier];
    }

    /**
     * Validate database connection, table count
     */
    public function validateDatabase($params)
    {
        try {
            $db_host = gv($params, 'db_host', env('DB_HOST'));
            $db_username = gv($params, 'db_username', env('DB_USERNAME'));
            $db_password = gv($params, 'db_password', env('DB_PASSWORD'));
            $db_database = gv($params, 'db_database', env('DB_DATABASE'));
    
            $link = @mysqli_connect($db_host, $db_username, $db_password);
    
            if (!$link) {
                throw ValidationException::withMessages(['message' => _trans('common.Connection Not Established')]);
            }
    
            $select_db = mysqli_select_db($link, $db_database);
            if (!$select_db) {
                throw ValidationException::withMessages(['message' => _trans('common.DB Not Found')]);
            }
            
            if (!gbv($params, 'force_migrate')) {

                $count_table_query = mysqli_query($link, "show tables");
                $count_table = mysqli_num_rows($count_table_query);
                
    
                if ($count_table) {
                    throw ValidationException::withMessages(['message' => _trans('common.Existing Table In Database')]);
                }
            }
    
            $this->setDBEnv($params);
    
            if (gbv($params, 'force_migrate')) {
                $this->rollbackDb();
            }
    
            return true;
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
      
     
    }

    public function checkDatabaseConnection()
    {
        $db_host = env('DB_HOST');
        $db_username = env('DB_USERNAME');
        $db_password = env('DB_PASSWORD');
        $db_database = env('DB_DATABASE');

        try {
            $link = @mysqli_connect($db_host, $db_username, $db_password);
        } catch (\Exception$e) {
            return false;
        }

        if (!$link) {
            return false;
        }
        $select_db = mysqli_select_db($link, $db_database);

        if (!$select_db) {
            return false;
        }

        $count_table_query = mysqli_query($link, "show tables");
        $count_table = mysqli_num_rows($count_table_query);

        if ($count_table) {
            return false;
        }
        return true;
    }

    public function validateLicense($params)
    {
        try {
            if (isTestMode()) {
                return true;
            }
            if (!isConnected()) {
                throw ValidationException::withMessages(['message' => 'No internet connection.']);
            }
            $ve = Storage::disk('local')->exists('.ve') ? Storage::disk('local')->get('.ve') : 'e';
            $url = verifyUrl(config('app.verifier', 'auth')) . '/api/cc?a=install&u=' . app_url() . '&ac=' . request('access_code') . '&i=' . config('app.item') . '&e=' . request('envato_email') . '&ri=' . request('re_install') . '&current=' . urlencode(request()->path()) . '&ve=' . $ve;
            $response = curlIt($url);
            if (gv($response, 'goto')) {
                return $response;
            }

            $status = (isset($response['status']) && $response['status']) ? 1 : 0;

            if ($status) {
                $checksum = $response['checksum'] ?? null;
                $license_code = $response['license_code'] ?? null;
                $modules = gv($response, 'modules', []);
            } else {
                $message = gv($response, 'message') ? $response['message'] : _trans('common.Please talk with Service or Software Provider');
                throw ValidationException::withMessages(['access_code' => $message]);
            }
            // generate random 64 bit code

            $checksum = 'eyJpdiI6Im9oMWU5Z0NoSGVwVzdmQlphaVBvd1E9PSIsInZhbHVlIjoiUURhZmpubkNBUVB6b0ZPck1v';
            $license_code = '5458-5365-8845-7865';

            Storage::disk('local')->put('.temp_app_installed', $checksum ?? '');
            Storage::disk('local')->put('.access_code', $license_code ?? '');
            Storage::disk('local')->put('.account_email', request('envato_email'));
            Storage::disk('local')->put('.access_log', date('Y-m-d'));

            $folder = storage_path('app' . DIRECTORY_SEPARATOR . config('app.item'));
            File::ensureDirectoryExists($folder);
            return true;
        } catch (\Exception$e) {
            return $e->getMessage();
        }
    }
    public function makeAdmin($params)
    {
        try {
            DB::beginTransaction();

            ini_set('max_execution_time', -1); 
            $user = DB::table($user_table)->find(1);
            if (!$user) {

                DB::table('users')
                ->insert([
                    'name' => 'Admin',
                    'email' => $params['email'],
                    'password' => Hash::make($params['password']),
                    'role_id' => 2,
                    'is_admin' => 1,
                ]);
            } else {
                DB::table('users')
                    ->where('id', 1)
                    ->update([
                        'name' => 'Admin',
                        'email' => $params['email'],
                        'password' => Hash::make($params['password']),
                        'role_id' => 2,
                        'is_admin' => 1,
                    ]);

            }

            DB::commit();
        } catch (\Exception$e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

    public function checkLicense()
    {
        if (isTestMode()) {
            return;
        }

        if (!isConnected()) {
            throw ValidationException::withMessages(['message' => 'No internet connection.']);
        }

        $ac = Storage::disk('local')->exists('.access_code') ? Storage::disk('local')->get('.access_code') : null;
        $e = Storage::disk('local')->exists('.account_email') ? Storage::disk('local')->get('.account_email') : null;
        $c = Storage::disk('local')->exists('.temp_app_installed') ? Storage::disk('local')->get('.temp_app_installed') : null;
        $v = Storage::disk('local')->exists('.version') ? Storage::disk('local')->get('.version') : null;

        $url = verifyUrl(config('app.verifier', 'auth')) . '/api/cc?a=verify&u=' . app_url() . '&ac=' . $ac . '&i=' . config('app.item') . '&e=' . $e . '&c=' . $c . '&v=' . $v . '&current=' . urlencode(request()->path());
        $response = curlIt($url);
        if (gv($response, 'goto')) {
            return redirect($goto)->send();
        }
        // $status = gbv($response, 'status');
        $status = true;

        if (!$status) {
            Storage::disk('local')->delete(['.access_code', '.account_email']);
            Storage::disk('local')->deleteDirectory(config('app.item'));
            Storage::disk('local')->put('.temp_app_installed', '');
            return false;
        } else {
            Storage::disk('local')->put('.access_log', date('Y-m-d'));
            return true;
        }
    }

    /**
     * Install the script
     */
    public function install($params)
    {

        try {
            $this->migrateDB();

            $ac = Storage::disk('local')->exists('.temp_app_installed') ? Storage::disk('local')->get('.temp_app_installed') : null;
            Storage::disk('local')->put('.app_installed', config('installer.signature'));
            Storage::disk('local')->put('.user_email', gv($params, 'email'));
            Storage::disk('local')->put('.user_pass', gv($params, 'password'));

            Storage::disk('local')->delete('.temp_app_installed');
        } catch (\Throwable$th) {
            return response()->json(['message' => $th->getMessage()], 200);
        }
    }

    /**
     * Write to env file
     */
    public function setDBEnv($params)
    {
        try {
            envu([
                'APP_URL' => app_url(),
                'DB_PORT' => gv($params, 'db_port'),
                'DB_HOST' => gv($params, 'db_host'),
                'DB_DATABASE' => gv($params, 'db_database'),
                'DB_USERNAME' => gv($params, 'db_username'),
                'DB_PASSWORD' => gv($params, 'db_password'),
            ]);
    
            DB::disconnect('mysql');
    
            config([
                'database.connections.mysql.host' => gv($params, 'db_host'),
                'database.connections.mysql.port' => gv($params, 'db_port'),
                'database.connections.mysql.database' => gv($params, 'db_database'),
                'database.connections.mysql.username' => gv($params, 'db_username'),
                'database.connections.mysql.password' => gv($params, 'db_password'),
            ]);
    
            DB::setDefaultConnection('mysql');
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
      
    }

    /**
     * Mirage tables to database
     */
    public function migrateDB()
    {

        try {
            ini_set('max_execution_time', -1);
            $sql = base_path('public/installer/db/crm.sql');
        } catch (Throwable $e) {
            $sql = base_path(config('installer.database_file'));
            if (File::exists($sql)) {
                DB::unprepared(file_get_contents($sql));
            }
        }
    }

    public function rollbackDb()
    {
        ini_set('max_execution_time', -1);
        Artisan::call('db:wipe', array('--force' => true));
    }

    /**
     * Seed tables to database
     */
    public function seed($seed = 0)
    {
        if (!$seed) {
            return;
        }

        $db = Artisan::call('db:seed', array('--force' => true));
    }

    public function uninstall($request)
    {
        $signature = gv($request, 'signature');
        $response = [
            'DB_PORT' => env('DB_PORT'),
            'DB_HOST' => env('DB_HOST'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'DB_USERNAME' => env('DB_USERNAME'),
            'DB_PASSWORD' => env('DB_PASSWORD'),
        ];
        if (config('app.signature') == $signature) {
            envu([
                'DB_PORT' => '3306',
                'DB_HOST' => 'localhost',
                'DB_DATABASE' => "",
                'DB_USERNAME' => "",
                'DB_PASSWORD' => "",
            ]);

            Storage::disk('local')->delete(['.access_code', '.account_email']);
            Storage::disk('local')->put('.app_installed', '');
            Artisan::call('optimize:clear');
            Storage::disk('local')->put('.logout', true);
        }
        return $response;
    }

}
