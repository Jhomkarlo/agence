<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Services\{
    CaoUsuarioService,
    CaoFaturaService
};

use Illuminate\Http\Request;

class AgenceController extends Controller
{

    protected $cauUsuarioService;
    protected $caoFaturaService;

    public function __construct(
        CaoUsuarioService $cauUsuarioService,
        CaoFaturaService $caoFaturaService
    ) {
        $this->cauUsuarioService = $cauUsuarioService;
        $this->caoFaturaService = $caoFaturaService;
    }

    public function index()
    {
        return view('agence.index', [
            'consultorList' => $this->cauUsuarioService->getConsultorList(),
            'availableMonthList' => $this->caoFaturaService->getAvailableMonthList(),
        ]);
    }

    public function report(Request $request)
    {
        $params = [];

        $caoUsuarioList = [];
        if($request->query('consultores')) {
            foreach ($request->query('consultores') as $co_usuario) {
                if ($caoUsuario = $this->cauUsuarioService->get(['co_usuario' => trim($co_usuario)])) array_push($caoUsuarioList, $caoUsuario);
            }
        }
        if($request->query('from')) $params['from'] = $request->query('from');
        if($request->query('to')) $params['to'] = $request->query('to');

        return response()->json([
            'success' => true,
            'code'    => 'SUCCESS',
            'data'    => [
                'report' => $this->caoFaturaService->getReportByCaoUsuarioList($caoUsuarioList, $params),
            ],
        ]);
                
    }

}
