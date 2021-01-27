<?php

namespace App\Repositories;

use App\Models\CaoSalario;

use Illuminate\Support\Facades\DB;

class CaoSalarioRepository  
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
        $query = DB::table('cao_salario', 'CS')->select('*');

        if (array_key_exists('co_usuario', $params)) {
            $query->where('CS.co_usuario', $params['co_usuario']);
        }

        return $query;
    }
}
