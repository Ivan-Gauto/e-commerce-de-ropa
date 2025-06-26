<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Usuarios_model;

class Usuario_controller extends Controller
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    // Muestra todos los usuarios activos
    public function index()
    {
        $usuarioModel = new Usuarios_model();
        $buscar = $this->request->getGet('buscar');

        if ($buscar !== null && $buscar !== '') {
            $data['usuarios'] = $usuarioModel
                ->groupStart()
                ->like('id', $buscar)
                ->orLike('nombre', $buscar)
                ->orLike('apellido', $buscar)
                ->orLike('usuario', $buscar)
                ->orLike('email', $buscar)
                ->groupEnd()
                ->where('baja', 'NO')
                ->findAll();
        } else {
            $data['usuarios'] = $usuarioModel->where('baja', 'NO')->findAll();
        }


        $data['titulo'] = 'CRUD de Usuarios';
        $data['buscar'] = $buscar;

        echo view('front/head_view', $data);
        echo view('front/nav_view');
        echo view('back/usuarios/crud_usuarios_view', $data);
        echo view('front/footer_view');
    }

    // Muestra los usuarios eliminados
    public function eliminados()
    {
        $usuarioModel = new Usuarios_model();
        $buscar = $this->request->getGet('buscar');

        // Filtra los usuarios eliminados con la opción de búsqueda
        if ($buscar !== null && $buscar !== '') {
            $data['usuarios'] = $usuarioModel
                ->groupStart()
                ->like('id', $buscar)
                ->orLike('nombre', $buscar)
                ->orLike('apellido', $buscar)
                ->orLike('usuario', $buscar)
                ->orLike('email', $buscar)
                ->groupEnd()
                ->where('baja', 'SI')
                ->findAll();
        } else {
            $data['usuarios'] = $usuarioModel->where('baja', 'SI')->findAll();
        }


        $data['titulo'] = 'Usuarios eliminados';
        $data['buscar'] = $buscar;

        echo view('front/head_view', $data);
        echo view('front/nav_view');
        echo view('back/usuarios/usuarios_eliminados_view', $data);
        echo view('front/footer_view');
    }

    // Elimina un usuario (marca como baja)
    public function eliminar($id)
    {
        $usuarioModel = new Usuarios_model();

        // Verifica si el usuario existe
        $usuario = $usuarioModel->find($id);
        $perfil = $usuario['perfil_id'];

        if (!$usuario) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Usuario no encontrado");
        }

        if ($perfil == 1) {
            session()->setFlashdata('error', 'No se puede eliminar el usuario de administrador.');
        } else {
            // Marca el usuario como eliminado
            $usuarioModel->update($id, ['baja' => 'SI']);
            session()->setFlashdata('success', 'Usuario eliminado correctamente.');
        }

        return redirect()->to('/crud_usuarios_view');
    }

    // Realiza la validación de los datos del formulario para el registro de un usuario
    public function form_validation()
    {
        $input = $this->validate(
            [
                'nombre' => 'required|alpha_space|min_length[3]|max_length[25]',
                'apellido' => 'required|alpha_space|min_length[3]|max_length[25]',
                'email' => 'required|valid_email|max_length[50]',
                'usuario' => 'required|alpha_numeric|min_length[3]|max_length[20]',
                'pass' => 'required|min_length[6]|max_length[30]',
            ],
            [

            ]
        );

        $formModel = new Usuarios_model();

        // Comprobación de si el email o usuario ya están en uso
        $usuarioExistente = $formModel
            ->groupStart()
            ->where('email', $this->request->getVar('email'))
            ->orWhere('usuario', $this->request->getVar('usuario'))
            ->groupEnd()
            ->where('baja', 'NO')
            ->first();

        if ($usuarioExistente) {
            // Si existe un usuario con el mismo email o usuario, se muestra un error
            $validation = \Config\Services::validation();
            $validation->setError('email', 'El correo o nombre de usuario ya están en uso.');
        }

        // Si la validación falla
        if (!$input || $usuarioExistente) {
            $data['titulo'] = 'Registro';
            echo view('front/head_view', $data);
            echo view('front/nav_view');
            echo view('back/usuarios/agregarusuario_view', [
                'validation' => \Config\Services::validation()
            ]);
            echo view('front/footer_view');
            return;
        }

        // Guarda los datos si la validación es exitosa
        $formModel->save([
            'nombre' => $this->request->getVar('nombre'),
            'apellido' => $this->request->getVar('apellido'),
            'usuario' => $this->request->getVar('usuario'),
            'email' => $this->request->getVar('email'),
            'pass' => password_hash($this->request->getVar('pass'), PASSWORD_DEFAULT),
        ]);

        session()->setFlashdata('success', 'Se ha registrado con éxito :)');
        return redirect()->to('/iniciarsesion_view');
    }

    // Reactiva un usuario que estaba dado de baja
    public function activar_usuario($id)
    {
        $model = new Usuarios_model();

        // Verifica si el usuario existe
        $usuario = $model->find($id);

        if (!$usuario) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // Actualiza el campo 'baja' a 'NO' para activar el usuario
        $model->update($id, ['baja' => 'NO']);

        return redirect()->to(base_url('usuarios_eliminados_view'))->with('success', 'Usuario activado correctamente.');
    }

    // Permite editar los datos de un usuario desde el panel de administración
    public function editar_usuario_admin($id = null)
    {
        $usuarioModel = new Usuarios_Model();
        $usuario = $usuarioModel->where('id', $id)->first();

        if (!$this->request->is('post')) {
            // Muestra el formulario de edición
            $data['usuario'] = $usuario;
            $dato['titulo'] = "Editar perfil";
            echo view('front/head_view', $dato);
            echo view('front/nav_view');
            echo view('front/plantilla_perfil_admin', $data);
            echo view('front/footer_view');
            return;
        }

        // Valida los campos del formulario
        $rules = [
            'nombre' => 'required|alpha_space|min_length[3]|max_length[25]',
            'apellido' => 'required|alpha_space|min_length[3]|max_length[25]',
            'email' => 'required|valid_email|max_length[50]',
            'usuario' => 'required|alpha_numeric|min_length[3]|max_length[20]',
            'pass' => 'permit_empty|min_length[6]|max_length[30]',
        ];

        $messages = [

        ];

        if (!$this->validate($rules, $messages)) {
            $data['validation'] = $this->validator;
            $data['usuario'] = $usuario;
            $dato['titulo'] = "Editar perfil";

            echo view('front/head_view', $dato);
            echo view('front/nav_view');
            echo view('front/plantilla_perfil_admin', $data);
            echo view('front/footer_view');
            return;
        }

        // Actualiza los datos del usuario
        $updateData = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'email' => $this->request->getPost('email'),
            'usuario' => $this->request->getPost('usuario'),
        ];

        $pass = $this->request->getPost('pass');
        if (!empty($pass)) {
            $updateData['pass'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        // Actualiza los datos en la base de datos
        $usuarioModel->update($usuario['id'], $updateData);

        session()->setFlashdata('success', 'Usuario actualizado correctamente.');
        return redirect()->to(base_url('editar_usuario_admin/' . $usuario['id']));
    }

    // Permite que el usuario edite su propio perfil
    public function editar_usuario($id = null)
    {
        $session = session();
        $usuarioLogueadoId = $session->get('id');
        $perfil = $session->get('perfil_id');

        // Verifica si el usuario tiene permiso para editar este perfil
        if ($perfil != 1 && $usuarioLogueadoId != $id) {
            return redirect()->to('/plantilla_principal')->with('error', 'No tenés permiso para editar este usuario.');
        }

        $usuarioModel = new Usuarios_Model();
        $usuario = $usuarioModel->where('id', $id)->first();

        $accion = $this->request->getPost('accion');
        if ($accion === 'eliminar') {
            $usuarioModel->update($id, ['baja' => 'SI']);
            $session->destroy();
            return redirect()->to(base_url('/agregarusuario_view'));
        }

        $input = $this->validate([
            'nombre' => 'required|alpha_space|min_length[3]|max_length[25]',
            'apellido' => 'required|alpha_space|min_length[3]|max_length[25]',
            'email' => 'required|valid_email|max_length[50]',
            'usuario' => 'required|alpha_numeric|min_length[3]|max_length[20]',
            'pass' => 'permit_empty|min_length[6]|max_length[30]',
        ], [
            'nombre' => [
                'required' => 'El nombre es obligatorio.',
                'alpha_space' => 'El nombre solo puede contener letras y espacios.',
                'min_length' => 'El nombre debe tener al menos 3 caracteres.',
                'max_length' => 'El nombre no puede tener más de 25 caracteres.',
            ],
            'apellido' => [
                'required' => 'El apellido es obligatorio.',
                'alpha_space' => 'El apellido solo puede contener letras y espacios.',
                'min_length' => 'El apellido debe tener al menos 3 caracteres.',
                'max_length' => 'El apellido no puede tener más de 25 caracteres.',
            ],
            'email' => [
                'required' => 'El email es obligatorio.',
                'valid_email' => 'Debes ingresar un email válido.',
                'max_length' => 'El email no puede tener más de 50 caracteres.',
            ],
            'usuario' => [
                'required' => 'El usuario es obligatorio.',
                'alpha_numeric' => 'El usuario solo puede contener letras y números.',
                'min_length' => 'El usuario debe tener al menos 3 caracteres.',
                'max_length' => 'El usuario no puede tener más de 20 caracteres.',
            ],
            'pass' => [
                'min_length' => 'La contraseña debe tener al menos 6 caracteres.',
                'max_length' => 'La contraseña no puede tener más de 30 caracteres.',
            ],
        ]);

        if (!$input) {
            $data['validation'] = $this->validator;
            $data['usuario'] = $usuario;
            $dato['titulo'] = "Editar perfil";
            echo view('front/head_view', $dato);
            echo view('front/nav_view');
            echo view('front/plantilla_perfil', $data);
            echo view('front/footer_view');
            return;
        }

        // Actualiza el perfil con los nuevos datos
        $updateData = [
            'nombre' => $this->request->getVar('nombre'),
            'apellido' => $this->request->getVar('apellido'),
            'email' => $this->request->getVar('email'),
            'usuario' => $this->request->getVar('usuario'),
        ];

        $pass = $this->request->getVar('pass');
        if (!empty($pass)) {
            $updateData['pass'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        if ($usuarioModel->update($usuario['id'], $updateData)) {
            $session->set([
                'nombre' => $updateData['nombre'],
                'apellido' => $updateData['apellido'],
                'email' => $updateData['email'],
                'usuario' => $updateData['usuario'],
            ]);
            session()->setFlashdata('success', 'Modificación exitosa.');
        }

        return redirect()->to(base_url('/plantilla_perfil'));
    }
}
