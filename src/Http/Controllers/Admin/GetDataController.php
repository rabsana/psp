<?php

namespace Rabsana\Psp\Http\Controllers\Admin;

use Exception;
use Illuminate\Routing\Controller;

class GetDataController extends Controller
{

    public function users()
    {
        if (blank(config('rabsana-psp.getAllUsersTask'))) {
            throw new Exception("The get all users task did not defined");
        }

        $task = config('rabsana-psp.getAllUsersTask');
        $users = (new $task)->run();
        return $users;
    }

    public function currencies()
    {
        if (blank(config('rabsana-psp.getAllCurrenciesTask'))) {
            throw new Exception("The get all currencies task did not defined");
        }
        $task = config('rabsana-psp.getAllCurrenciesTask');
        $currencies = (new $task)->run();
        return $currencies;
    }
}
