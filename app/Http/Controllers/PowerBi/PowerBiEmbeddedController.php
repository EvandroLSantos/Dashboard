<?php

namespace App\Http\Controllers\PowerBi;

use App\Http\Controllers\Controller;
use App\Models\Model_has_Permission;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PowerbiHelper;

class PowerBiEmbeddedController extends Controller
{
    public $request, $user, $permission;
    public function __construct(
        Request $request,
        Model_has_Permission $permission,

    ) {
        $this->request = $request;
        $this->permission = $permission;

        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }



    public function index(Request $request)
    {       
        $permissions = $this->user->getAllPermissions();            
        $permissionPower = [];
        foreach ($permissions as $p) {
            if ($p->tp_permission == 'powerbi') {
                $permissionPower[] = $p->name;
            }
        }
        if (Helper::is_empty_object($permissionPower)) {
            return redirect()->route('admin.dashboard')->with('warning', 'Você não tem modulo de acesso para essa tela, entrar em contato com a TI');
        }

        $permissionPower = json_encode($permissionPower);

        $office360token = PowerbiHelper::getOffice360AccessToken();
        $groupID = env('GROUP_ID');
        $reportID = env('REPORT_ID_NORTE');

        if (!is_null($office360token)) {

            $url = 'https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/GenerateToken';
            $url = sprintf($url, $groupID, $reportID);
            // no workspace escolher o relatorio e pagar na url 
            //https://app.powerbi.com/groups/04d511b0-ded9-4952-9708-dfa331d1ee83/reports/8ee36fa6-5b54-49d4-8e66-713791f7fd3e/ReportSectiondecccb8482fa6cf4f797

            $header = [
                "Authorization:{$office360token->token_type} {$office360token->access_token}",
                "content-type: application/json",
            ];
            $data = '{
                "accessLevel": "View",
                "allowSaveAs": "false",
                "identities": [{
                    "username": "' . $this->user['email'] . '",
                    "roles": ' . $permissionPower . ',
                    "datasets": ["5dedec2a-a509-4e70-bbd5-6d19a549054f"]
                }]
            }';
            $content = PowerbiHelper::processPowerbiHttpRequest($url, $header, $data, 'POST');

            $title_page   = 'Rede Ivorecap - Norte';
            $user_auth    = $this->user;
            $uri         = $this->request->route()->uri();
            $variableValue = "teste";
            return view(
                'admin.diretoria.powerbi',
                compact(
                    'content',
                    'title_page',
                    'uri',
                    'groupID',
                    'reportID', 
                    'user_auth',
                    'title_page',
                    'uri',
                    'variableValue'
                )
            );
        }
    }
}
