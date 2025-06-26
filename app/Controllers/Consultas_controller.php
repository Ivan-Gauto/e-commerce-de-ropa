<?php
namespace App\Controllers;

use App\Models\Consultas_Model;

class Consultas_controller extends BaseController
{
    // Método para guardar una consulta
    public function guardar()
    {
        $model = new Consultas_Model();

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'correo' => $this->request->getPost('correo'),
            'telefono' => $this->request->getPost('telefono'),
            'mensaje' => $this->request->getPost('mensaje'),
        ];

        $model->save($data);

        return redirect()->to('/contacto')->with('mensaje', 'Consulta enviada correctamente.');
    }

    // Método para listar todas las consultas
    public function listar()
    {
        $model = new Consultas_Model();

        // Solo traer las NO leídas
        $data['consultas'] = $model
            ->where('leida', 'NO')
            ->orderBy('fecha', 'DESC')
            ->findAll();

        echo view('front/head_view', ['titulo' => 'Consultas']);
        echo view('front/nav_view');
        echo view('front/consultas_view', $data);
        echo view('front/footer_view');
    }

    public function marcarComoLeida($id)
    {
        $model = new Consultas_Model();

        $consulta = $model->find($id);

        if (!$consulta) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Consulta no encontrada");
        }

        $model->update($id, ['leida' => 'SI']);

        return redirect()->to('consultas_view')->with('mensaje', 'Consulta marcada como leída.');
    }

    public function leidas()
    {
        $model = new Consultas_Model();

        $data['consultas'] = $model->where('leida', 'SI')->orderBy('fecha', 'DESC')->findAll();

        echo view('front/head_view', ['titulo' => 'Consultas Leídas']);
        echo view('front/nav_view');

        echo view('front/consultas_leidas_view', $data);
        echo view('front/footer_view');
    }

}
