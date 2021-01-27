<?php

namespace App\Repositories;

use App\Models\CaoUsuario;

use Illuminate\Support\Facades\DB;

class CaoUsuarioRepository  
{
    public function get($params)
    {
        return $this->query($params)->first();
    }

    public function getList($params = [])
    {
        return $this->query($params)->get();
    }

    public function query($params = [])
    {
        $query = DB::table('cao_usuario', 'CU')->select('*');

        if (array_key_exists('co_usuario', $params)) {
            $query->where('CU.co_usuario', $params['co_usuario']);
        }

        if (array_key_exists('in.co_usuario', $params)) {
            $query->whereIn('CU.co_usuario', $params['in.co_usuario']);
        }

        return $query;
    }

    public function getConsultorList()
    {
        return DB::table('cao_usuario', 'CU')
                    ->select('*')
                    ->join('permissao_sistema', 'CU.co_usuario', '=', 'permissao_sistema.co_usuario')
                    ->where('permissao_sistema.co_sistema', '=', 1)
                    ->where('permissao_sistema.in_ativo', '=', 'S')
                    ->whereIn('permissao_sistema.co_tipo_usuario', [0,1,2])
                    ->get();
    }
}
