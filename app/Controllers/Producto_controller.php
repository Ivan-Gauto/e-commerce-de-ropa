<?php

namespace App\Controllers;

use App\Models\Producto_model;
use App\Models\Usuarios_model;
use App\Models\Genero_model;
use App\Models\Edad_model;
use App\Models\Marca_model;
use App\Models\Talle_model;
use App\Models\Categoria_model;
use CodeIgniter\Controller;

class Producto_controller extends Controller
{
    public function __construct()
    {
        helper(['url', 'form']);
        $session = session();
    }

    // Muestra los productos en una lista
    public function index()
    {
        $productoModel = new Producto_Model();

        $buscar = $this->request->getGet('buscar');
        $data['productos'] = $productoModel->buscarProductos($buscar);
        $data['buscar'] = $buscar;

        $dato['titulo'] = 'Crud_productos';
        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/crud_productos_view', $data);
        echo view('front/footer_view');
    }

    // Muestra un solo producto para su edición
    public function singleProducto($id = null)
    {
        $productoModel = new Producto_model();
        $data['old'] = $productoModel->where('id_producto', $id)->first();

        if (empty($data['old'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No se pudo encontrar el producto seleccionado');
        }

        $categoriasModel = new Categoria_model();
        $data['categorias'] = $categoriasModel->getCategorias();
        $data['categoriaActual'] = $categoriasModel->where('id_categoria', $data['old']['categoria_id'])->first();

        $marcaModel = new Marca_model();
        $data['marcas'] = $marcaModel->getMarcas();
        $data['marcaActual'] = $marcaModel->where('id_marca', $data['old']['marca_id'])->first();

        $talleModel = new Talle_model();
        $data['talles'] = $talleModel->getTalles();
        $data['talleActual'] = $talleModel->where('id_talle', $data['old']['talle_id'])->first();

        $generoModel = new Genero_model();
        $data['generos'] = $generoModel->getGeneros();
        $data['generoActual'] = $generoModel->where('id_genero', $data['old']['genero_id'])->first();

        $edadModel = new Edad_model();
        $data['edades'] = $edadModel->getedades();
        $data['edadActual'] = $edadModel->where('id_edad', $data['old']['edad_id'])->first();

        $dato['titulo'] = 'Editar productos';
        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/editar_productos_view', $data);
        echo view('front/footer_view');
    }

    // Muestra el formulario para crear un nuevo producto
    public function crearProducto()
    {
        $categoriaModel = new Categoria_model();
        $marcaModel = new Marca_model();
        $talleModel = new Talle_model();
        $generoModel = new Genero_model();
        $edadModel = new Edad_model();

        $data['categorias'] = $categoriaModel->getCategorias();
        $data['marcas'] = $marcaModel->getMarcas();
        $data['talles'] = $talleModel->getTalles();
        $data['generos'] = $generoModel->getGeneros();
        $data['edades'] = $edadModel->getEdades();

        $dato['titulo'] = 'Alta producto';
        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/alta_productos_view', $data);
        echo view('front/footer_view');
    }

    // Guarda un nuevo producto en la base de datos
    public function store()
    {
        $productoModel = new Producto_Model();

        // Validación completa
        $input = $this->validate([
            'nombre_prod' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'El nombre del producto es obligatorio.',
                    'min_length' => 'Debe tener al menos 3 caracteres.'
                ]
            ],
            'categorias' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe seleccionar una categoría.'
                ]
            ],
            'marcas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe seleccionar una marca.'
                ]
            ],
            'talles' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe seleccionar un talle.'
                ]
            ],
            'generos' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe seleccionar un género.'
                ]
            ],
            'edades' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Debe seleccionar una edad.'
                ]
            ],
            'precio_costo' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El precio de costo es obligatorio.'
                ]
            ],
            'precio_venta' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'El precio de venta es obligatorio.'
                ]
            ],
            'stock' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'El stock es obligatorio.',
                    'integer' => 'El stock debe ser un número entero.'
                ]
            ],
            'stock_min' => [
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'El stock mínimo es obligatorio.',
                    'integer' => 'El stock mínimo debe ser un número entero.'
                ]
            ]
        ]);

        // Si falla la validación
        if (!$input) {
            session()->setFlashdata('error', 'Debe completar todos los campos.');
            return redirect()->to(base_url('/alta_productos_view'))->withInput();
        }

        // Validación de imagen
        $img = $this->request->getFile('imagen');
        if (!$img || !$img->isValid()) {
            session()->setFlashdata('error', 'Debe subir una imagen para el producto.');
            return redirect()->to(base_url('/alta_productos_view'))->withInput();
        }

        // Guardado de imagen
        $nombre_aleatorio = $img->getRandomName();
        $img->move(ROOTPATH . 'public/assets/uploads', $nombre_aleatorio);

        // Armado de datos
        $data = [
            'nombre_prod' => $this->request->getVar('nombre_prod'),
            'categoria_id' => $this->request->getVar('categorias'),
            'marca_id' => $this->request->getVar('marcas'),
            'talle_id' => $this->request->getVar('talles'),
            'genero_id' => $this->request->getVar('generos'),
            'edad_id' => $this->request->getVar('edades'),
            'precio_costo' => $this->convertir_a_float($this->request->getVar('precio_costo')),
            'precio_venta' => $this->convertir_a_float($this->request->getVar('precio_venta')),
            'stock' => $this->request->getVar('stock'),
            'stock_min' => $this->request->getVar('stock_min'),
            'imagen' => $nombre_aleatorio,
            'eliminado' => 'NO'
        ];

        // Insertar producto
        $productoModel->insert($data);
        session()->setFlashdata('success', 'Producto creado correctamente.');
        return redirect()->to(base_url('/crud_productos_view'));
    }


    private function convertir_a_float($valor)
    {
        return floatval(str_replace(['.', ','], ['', '.'], $valor));
    }

    // Restaura un producto eliminado
    public function restaurar_producto($id)
    {
        $productoModel = new Producto_Model();
        $productoModel->update($id, ['eliminado' => 'NO']);
        return redirect()->to(base_url('/productos_eliminados'))->with('success', 'Producto restaurado correctamente.');
    }

    // Elimina un producto (soft delete)
    public function eliminar_producto($id)
    {
        $productoModel = new Producto_model();
        $producto = $productoModel->find($id);

        if (!$producto) {
            return redirect()->back()->with('error', 'Producto no encontrado');
        }

        // Soft delete
        $productoModel->update($id, ['eliminado' => 'SI']);
        return redirect()->to('crud_productos_view')->with('success', 'Producto eliminado correctamente');
    }

    // Muestra los productos eliminados
    public function productos_eliminados()
    {
        $productoModel = new Producto_Model();
        $buscar = $this->request->getGet('buscar');

        // Filtra productos eliminados
        $productos = $productoModel->where('eliminado', 'SI');

        if (!empty($buscar)) {
            $productos = $productos->groupStart()
                ->like('nombre_prod', $buscar)
                ->orLike('id_producto', $buscar)
                ->groupEnd();
        }

        $data['productos'] = $productos->findAll();
        $data['buscar'] = $buscar;

        $dato['titulo'] = 'Productos eliminados';
        echo view('front/head_view', $dato);
        echo view('front/nav_view');
        echo view('back/productos/productos_eliminados_view', $data);
        echo view('front/footer_view');
    }

    // Edita un producto existente
    public function editar_producto($id = null)
    {
        helper(['form']);
        $productoModel = new Producto_Model();
        $producto = $productoModel->find($id);
    
        // Validaciones
        $input = $this->validate([
            'nombre_prod'   => 'required|min_length[3]',
            'categorias'    => 'required',
            'marcas'        => 'required',
            'talles'        => 'required',
            'generos'       => 'required',
            'edades'        => 'required',
            'precio_costo'  => 'required|numeric',
            'precio_venta'  => 'required|numeric',
            'stock'         => 'required|integer',
            'stock_min'     => 'required|integer',
        ], [
            'nombre_prod' => [
                'required'   => 'El nombre del producto es obligatorio.',
                'min_length' => 'Debe tener al menos 3 caracteres.',
            ],
            'categorias' => ['required' => 'Debes seleccionar una categoría.'],
            'marcas'     => ['required' => 'Debes seleccionar una marca.'],
            'talles'     => ['required' => 'Debes seleccionar un talle.'],
            'generos'    => ['required' => 'Debes seleccionar un género.'],
            'edades'     => ['required' => 'Debes seleccionar una edad.'],
            'precio_costo' => [
                'required' => 'El precio de costo es obligatorio.',
                'numeric'  => 'Debe ser un número.',
            ],
            'precio_venta' => [
                'required' => 'El precio de venta es obligatorio.',
                'numeric'  => 'Debe ser un número.',
            ],
            'stock' => [
                'required' => 'El stock es obligatorio.',
                'integer'  => 'Debe ser un número entero.',
            ],
            'stock_min' => [
                'required' => 'El stock mínimo es obligatorio.',
                'integer'  => 'Debe ser un número entero.',
            ],
        ]);
    
        $categoriaModel = new Categoria_model();
        $marcaModel = new Marca_model();
        $talleModel = new Talle_model();
        $generoModel = new Genero_model();
        $edadModel = new Edad_model();
        
        $categorias = $categoriaModel->findAll();
        $marcas = $marcaModel->findAll();
        $talles = $talleModel->findAll();
        $generos = $generoModel->findAll();
        $edades = $edadModel->findAll();
        
        if (!$input) {

            session()->setFlashdata('error', 'Debe completar todos los campos correctamente.');

            return view('front/head_view', ['titulo' => 'Editar Producto'])
                . view('front/nav_view')
                . view('back/productos/editar_productos_view', [
                    'validation'      => $this->validator,
                    'old'             => $producto,
                    'categoriaActual' => ['id_categoria' => $producto['categoria_id']],
                    'marcaActual'     => ['id_marca'     => $producto['marca_id']],
                    'talleActual'     => ['id_talle'     => $producto['talle_id']],
                    'generoActual'    => ['id_genero'    => $producto['genero_id']],
                    'edadActual'      => ['id_edad'      => $producto['edad_id']],
                    'categorias'      => $categorias,
                    'marcas'          => $marcas,
                    'talles'          => $talles,
                    'generos'         => $generos,
                    'edades'          => $edades,
                ])
                . view('front/footer_view');
        }
    
        // Si pasa validación, guardar datos
        $img = $this->request->getFile('imagen');
    
        $data = [
            'nombre_prod'  => $this->request->getVar('nombre_prod'),
            'id_categoria' => $this->request->getVar('categorias'),
            'id_marca'     => $this->request->getVar('marcas'),
            'id_talle'     => $this->request->getVar('talles'),
            'id_genero'    => $this->request->getVar('generos'),
            'id_edad'      => $this->request->getVar('edades'),
            'precio_costo' => $this->request->getVar('precio_costo'),
            'precio_venta' => $this->request->getVar('precio_venta'),
            'stock'        => $this->request->getVar('stock'),
            'stock_min'    => $this->request->getVar('stock_min'),
            'eliminado'    => 'NO',
        ];
    
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $rutaDestino = ROOTPATH . 'public/assets/uploads';
            $nombre_aleatorio = $img->getRandomName();
            $img->move($rutaDestino, $nombre_aleatorio);
            $data['imagen'] = $nombre_aleatorio;
        }
    
        $productoModel->update($id, $data);
    
        session()->setFlashdata('success', 'Producto actualizado exitosamente.');
        return redirect()->to(base_url('/editar_productos_view/' . $id));
    }
    


    // Muestra el catálogo de productos con filtros
    public function catalogo()
    {
        $productoModel = new Producto_model();
        $generoModel = new Genero_model();
        $edadModel = new Edad_model();
        $categoriaModel = new Categoria_model();
        $marcaModel = new Marca_model();

        $filtros = [
            'genero' => $generoModel->findAll(),
            'edad' => $edadModel->findAll(),
            'categoria' => $categoriaModel->findAll(),
            'marca' => $marcaModel->findAll(),
        ];

        $productos = $productoModel->where('eliminado', 'NO');

        // filtros opcionales
        $generoId = $this->request->getGet('genero');
        $edadId = $this->request->getGet('edad');
        $categoriaId = $this->request->getGet('categoria');
        $marcaId = $this->request->getGet('marca');

        if ($generoId)
            $productos->where('genero_id', $generoId);
        if ($edadId)
            $productos->where('edad_id', $edadId);
        if ($categoriaId)
            $productos->where('categoria_id', $categoriaId);
        if ($marcaId)
            $productos->where('marca_id', $marcaId);

        // paginación
        $data['productos'] = $productos->paginate(5);
        $data['pager'] = $productoModel->pager;

        $data['titulo'] = 'Catálogo de Productos';
        $data['generos'] = $filtros['genero'];
        $data['edades'] = $filtros['edad'];
        $data['categorias'] = $filtros['categoria'];
        $data['marcas'] = $filtros['marca'];

        echo view('front/head_view', ['titulo' => $data['titulo']]);
        echo view('front/nav_view');
        echo view('front/panel-carrito');
        echo view('front/catalogo_productos_view', $data);
        echo view('front/footer_view');
    }

}
