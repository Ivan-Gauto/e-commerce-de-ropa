<?php

namespace App\Models;

use CodeIgniter\Model;

class Edad_model extends Model
{

    protected $table = 'Edades';
    protected $primaryKey = 'id_Edad';
    protected $allowedFields = ['nombre'];

    /**
     * Devuelve todos los registros de la tabla 'Edades'.
     */
    public function getEdades()
    {
        return $this->findAll();
    }
}
