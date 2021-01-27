<?php

namespace App\Services;

use App\Models\CaoUsuario;
use App\Repositories\{
    CaoFaturaRepository,
    CaoUsuarioRepository,
    CaoSalarioRepository,
};

class CaoFaturaService
{
    protected $caoFaturaRepository;
    protected $caoUsuarioRepository;
    protected $caoSalarioRepository;

    public function __construct(
        CaoFaturaRepository $caoFaturaRepository,
        CaoUsuarioRepository $caoUsuarioRepository,
        CaoSalarioRepository $caoSalarioRepository
    ) {
        $this->caoFaturaRepository = $caoFaturaRepository;
        $this->caoUsuarioRepository = $caoUsuarioRepository;
        $this->caoSalarioRepository = $caoSalarioRepository;
    }

    public function getAvailableMonthList()
    {
        return $this->caoFaturaRepository->getAvailableMonthList();
    }

    public function getReportByCaoUsuarioList($caoUsuarioList, $dateRange = [])
    {
        return collect($caoUsuarioList)->map(function ($caoUsuario) use ($dateRange) {
            $caoSalario = $this->caoSalarioRepository->get(['co_usuario' => $caoUsuario->co_usuario]);
            return [
                'cao_usuario' => $caoUsuario,
                'cao_salario' => $caoSalario,
                'report' => $this->getReportByCaoUsuario($caoUsuario, $dateRange)
            ];
        });
    }

    public function getReportByCaoUsuario($caoUsuario, $dateRange = [])
    {
        $params = ['co_usuario' => $caoUsuario->co_usuario];

        if (array_key_exists('from', $dateRange)) {
            $params['from_year_month'] = (strlen($dateRange['from']) == 6)?$dateRange['from']:date('Ym', strtotime($dateRange['from']));
        }

        if (array_key_exists('to', $dateRange)) {
            $params['to_year_month'] = (strlen($dateRange['to']) == 6)?$dateRange['to']:date('Ym', strtotime($dateRange['to']));
        }

        return $this->caoFaturaRepository->getReport($params);
    }
}
