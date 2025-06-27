<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Auth implements FilterInterface 
{
    /**
     * Este método se ejecuta antes de acceder a una ruta protegida.
     * Verifica si el usuario está logueado y si tiene el perfil requerido (admin o cliente).
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica si el usuario ha iniciado sesión
        if (!session()->get('logged_in')) {
            return redirect()->to('/iniciarsesion_view');
        }

        // Verifica roles si se pasaron argumentos (por ejemplo: ['admin'], ['cliente'])
        if ($arguments) {
            // Verifica si se requiere rol 'admin' y el usuario no lo tiene
            if (in_array('admin', $arguments)) {
                if (session()->get('perfil_id') != 1) {
                    session()->setFlashdata('error', 'Acceso denegado, debe iniciar sesion con un usuario permitido');
                    return redirect()->to('/iniciarsesion_view');
                }
            }

            // Verifica si se requiere rol 'cliente' y el usuario no lo tiene
            if (in_array('cliente', $arguments)) {
                if (session()->get('perfil_id') != 2) {
                    session()->setFlashdata('error', 'Acceso denegado, debe iniciar sesion con un usuario permitido');
                    return redirect()->to('/iniciarsesion_view');
                }
            }
        }
    }

    /**
     * Método opcional que se ejecuta después de la ejecución del controlador.
     * En este caso, no se aplica ninguna lógica post-procesamiento.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se modifica
    }
}
