<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use RachidLaasri\LaravelInstaller\Events\EnvironmentSaved;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Classic page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Classic).
     *
     * @param Request $input
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveClassic(Request $input, Redirector $redirect)
    {
        $this->EnvironmentManager->saveFileClassic($input);

        event(new EnvironmentSaved($input));

        return $redirect->route('LaravelInstaller::environmentCheckConnection');
    }

    /**
     * Checks DB connection
     *
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    //
    public function checkConnection(Redirector $redirect)
    {
        if (!$this->checkDatabaseConnection()) {
            return $redirect->route('LaravelInstaller::environment')->withErrors([
                'envConfig' => 'Could not connect to the database.',
            ]);
        }

        return $redirect->route('LaravelInstaller::environment')->with(['checked' => true]);
    }

    /**
     * Validate database connection with user credentials
     *
     * @return bool
     */
    private function checkDatabaseConnection()
    {   
        DB::purge();

        try {
            DB::connection()->getPdo();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
