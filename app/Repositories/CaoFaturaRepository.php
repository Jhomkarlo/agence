<?php

// SELECT SUM(CF.total) as sum_total, SUM(CF.valor) as sum_valor, SUM(CF.valor - (CF.valor * (CF.total_imp_inc / 100))) AS receita_liquida, SUM((CF.valor - (CF.valor * (CF.total_imp_inc / 100))) * (CF.comissao_cn / 100)) as comissao, EXTRACT(YEAR_MONTH FROM CF.data_emissao) AS data_emissao_year_month FROM cao_fatura CF
// INNER JOIN cao_os CO ON CF.co_os = CO.co_os
// INNER JOIN cao_usuario CU ON CO.co_usuario = CU.co_usuario
// WHERE CU.co_usuario = 'carlos.arruda' AND EXTRACT(YEAR_MONTH FROM CF.data_emissao) >= '200701' AND EXTRACT(YEAR_MONTH FROM CF.data_emissao) <= '200702'
// GROUP BY EXTRACT(YEAR_MONTH FROM CF.data_emissao)

namespace App\Repositories;

use App\Models\CaoFatura;
use Illuminate\Support\Facades\DB;

class CaoFaturaRepository  
{
    public function getReport($params = [])
    {
        $query = DB::table('cao_fatura', 'CF')
                    ->select(DB::raw('SUM(CF.total) AS sum_total'))
                    ->addSelect(DB::raw('SUM(CF.valor) AS sum_valor'))
                    ->addSelect(DB::raw('SUM(CF.valor - (CF.valor * (CF.total_imp_inc / 100))) AS receita_liquida'))
                    ->addSelect(DB::raw('SUM((CF.valor - (CF.valor * (CF.total_imp_inc / 100))) * (CF.comissao_cn / 100)) as comissao'))
                    ->addSelect(DB::raw('EXTRACT(YEAR_MONTH FROM CF.data_emissao) AS data_emissao_year_month'));

        if (array_key_exists('co_usuario', $params)) {
            $query = $query->join('cao_os', 'CF.co_os', '=', 'cao_os.co_os')
                            ->join('cao_usuario', 'cao_os.co_usuario', '=', 'cao_usuario.co_usuario')
                            ->where('cao_usuario.co_usuario', $params['co_usuario']);
        }

        if (array_key_exists('from_year_month', $params) && $params['from_year_month']) {
            $query->whereRaw('EXTRACT(YEAR_MONTH FROM CF.data_emissao) >= ?', [$params['from_year_month']]);
        }

        if (array_key_exists('to_year_month', $params) && $params['to_year_month']) {
            $query->whereRaw('EXTRACT(YEAR_MONTH FROM CF.data_emissao) <= ?', [$params['to_year_month']]);
        }
        
        $query->groupByRaw('EXTRACT(YEAR_MONTH FROM CF.data_emissao)');

        return $query->get();
    }

    public function getAvailableMonthList()
    {
        $query = DB::table('cao_fatura', 'CF')
                    ->select(DB::raw('COUNT(CF.co_fatura) AS count_fatura'))
                    ->addSelect(DB::raw('DATE_FORMAT(CF.data_emissao, "%Y-%m") AS data_emissao_year_month_formatted'));
        $query->groupByRaw('DATE_FORMAT(CF.data_emissao, "%Y-%m")');
        return $query->get();
    }
}
