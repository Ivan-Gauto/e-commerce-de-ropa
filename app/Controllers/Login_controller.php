<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Usuarios_model;

class Login_controller extends Controller
{
    // Muestra la vista principal de login
    public function index()
    {
        helper(['form', 'url']);
    }

    // Autentica al usuario: valida usuario y contraseña
    public function Auth()
    {
        $session = session();
        $model = new Usuarios_model();

        $usuario = $this->request->getVar('usuario');
        $password = $this->request->getVar('pass');

        // Busca el usuario en la base de datos
        $data = $model
            ->where('usuario', $usuario)
            ->where('baja', 'NO')  // solo usuarios activos
            ->orderBy('id', 'DESC')  // toma el más reciente
            ->first();

        if ($data) {
            if ($data['baja'] === 'SI') {
                $session->setFlashdata('msg', 'El usuario está dado de baja.');
                return redirect()->to('/iniciarsesion_view');
            }

            if (password_verify($password, $data['pass'])) {
                $ses_data = [
                    'id' => $data['id'],
                    'nombre' => $data['nombre'],
                    'apellido' => $data['apellido'],
                    'email' => $data['email'],
                    'usuario' => $data['usuario'],
                    'perfil_id' => $data['perfil_id'],
                    'logged_in' => true,
                ];
                $session->set($ses_data);
                $session->setFlashdata('welcome_message', '¡Bienvenido!');

                // Redirige según el perfil del usuario
                if ($data['perfil_id'] == 1) {
                    return redirect()->to('/crud_productos_view');
                } else {
                    return redirect()->to('/plantilla_principal');
                }
            } else {
                $session->setFlashdata('error_password', 'Contraseña incorrecta');
                return redirect()->to('/iniciarsesion_view');
            }
        } else {
            $session->setFlashdata('error_usuario', 'Usuario no valido');
            return redirect()->to('/iniciarsesion_view');
        }
    }

    // Muestra la información del perfil del usuario
    public function buscar_usuario()
    {
        $session = session();
        $id = $session->get('id');
        $usuario_model = new Usuarios_model();
        $usuario = $usuario_model->find($id);
        $data['titulo'] = 'Mi informacion';
        $data['usuario'] = $usuario;

        echo view('front/head_view', $data);
        echo view('front/nav_view');
        echo view('front/panel-carrito');
        echo view('front/plantilla_perfil', $data);
        echo view('front/boton_inicio');
        echo view('front/footer_view');
    }

    // Cierra la sesión del usuario
    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/iniciarsesion_view');
    }
}
