<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Producto_model;
use App\Models\Ventas_cabecera_model;
use App\Models\Ventas_detalle_model;

class Ventas_controller extends Controller
{
    // Registra una venta, guarda la cabecera y los detalles, y actualiza el stock
    public function registrar_venta()
    {
        $session = session();
        $cart = \Config\Services::cart();
        $carrito = $cart->contents();

        $productoModel = new Producto_model();
        $ventasModel = new Ventas_cabecera_model();
        $detalleModel = new Ventas_detalle_model();

        $productos_validos = [];
        $productos_sin_stock = [];
        $total = 0;

        if (!empty($carrito)) {
            $session->setFlashdata('error', 'El carrito está vacío. No se puede realizar una compra.');
            return redirect()->to(site_url('catalogo_productos_view'));
        }        

        // Verifica que haya suficiente stock para cada producto en el carrito
        foreach ($carrito as $item) {
            $producto = $productoModel->find($item['id']);

            if ($producto && $producto['stock'] >= $item['qty']) {
                $productos_validos[] = $item;
                $total += $item['subtotal'];
            } else {
                $productos_sin_stock[] = $item['name'];
                $cart->remove($item['rowid']);
            }
        }

        // Si hay productos sin stock, muestra un mensaje y redirige al carrito
        if (!empty($productos_sin_stock)) {
            $mensaje = 'Los siguientes productos no tienen stock suficiente y fueron eliminados del carrito: <br>' . implode(', ', $productos_sin_stock);
            $session->setFlashdata('mensaje', $mensaje);
            return redirect()->to(site_url('carrito_view'));
        }

        // Si no hay productos válidos, muestra un mensaje y redirige
        if (empty($productos_validos)) {
            $session->setFlashdata('mensaje', 'No hay productos válidos para registrar la venta.');
            return redirect()->to(site_url('carrito_view'));
        }

        // Insertar cabecera de venta
        $venta_id = $ventasModel->insert([
            'usuario_id' => $session->get('id'),
            'total_venta' => $total,
            'fecha' => date('Y-m-d H:i:s')
        ]);

        // Insertar detalles de cada producto vendido
        foreach ($productos_validos as $item) {
            $detalleModel->insert([
                'venta_id' => $venta_id,
                'producto_id' => $item['id'],
                'cantidad' => $item['qty'],
                'precio' => $item['price']
            ]);

            // Actualizar el stock del producto
            $producto = $productoModel->find($item['id']);
            $nuevoStock = $producto['stock'] - $item['qty'];
            $productoModel->update($item['id'], ['stock' => $nuevoStock]);
        }

        // Vaciar el carrito y redirigir a la factura
        $cart->destroy();

        $session->setFlashdata('success', 'Compra realizada con éxito.');
        return redirect()->to(site_url('ver_factura/' . $venta_id));
    }

    // Muestra la factura de una venta específica
    public function ver_factura($venta_id)
    {
        $detalleModel = new Ventas_detalle_model();
        $data['detalles'] = $detalleModel->getDetalles($venta_id); 
        $dato['titulo'] = "Factura de compra";

        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/compras/ver_factura_usuario', $data); 
        echo view('front/footer_view');
    }

    // Muestra todas las facturas del usuario
    public function ver_facturas_usuario()
    {
        $session = session();
        $usuario_id = $session->get('id');

        $ventasModel = new Ventas_cabecera_model();
        $data['ventas'] = $ventasModel->where('usuario_id', $usuario_id)
                                      ->orderBy('fecha', 'DESC')
                                      ->findAll();

        $dato['titulo'] = 'Mis Compras';

        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/compras/ver_factura_usuario', $data);
        echo view('front/footer_view');
    }

    // Muestra las compras de un usuario en su perfil
    public function misCompras()
    {
        $session = session();
        $usuario_id = $session->get('id_usuario');

        $ventasModel = new Ventas_cabecera_model();
        $data['ventas'] = $ventasModel->getVentas($usuario_id);

        $dato['titulo'] = 'Mis Compras';

        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/compras/compras_view', $data);
        echo view('front/footer_view');
    }

    // Muestra todas las ventas (admin)
    public function todasLasVentas()
    {
        $ventasModel = new Ventas_cabecera_model();
        $data['ventas'] = $ventasModel->getVentas(); // sin parámetro: trae todas

        $dato['titulo'] = 'Historial de Ventas';

        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/compras/ventas_admin_view', $data); // Vista del admin
        echo view('front/footer_view');
    }
}
