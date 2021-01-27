<?php

namespace App\Services;

use App\Repositories\CaoUsuarioRepository;

class CaoUsuarioService
{

    protected $caoUsuariosRepository;

    public function __construct(CaoUsuarioRepository $caoUsuariosRepository)
    {
        $this->caoUsuariosRepository = $caoUsuariosRepository;
    }

    public function get($co_usuario)
    {
        return $this->caoUsuariosRepository->get($co_usuario);
    }

    public function getList($params = [])
    {
        return $this->caoUsuariosRepository->getList($params);
    }

    public function getConsultorList()
    {
        return $this->caoUsuariosRepository->getConsultorList();
    }
}
