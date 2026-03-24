<?php

/**
 * Plantilla general de controladores
 * @version 2.0.0
 *
 * Controlador de albums
 */
class albumsController extends Controller implements ControllerInterface
{
  function __construct()
  {
    // Prevenir el ingreso si nos encontramos en producción y esta ruta es sólo para desarrollo o pruebas
    // if (!is_local()) {
    //   Redirect::to(DEFAULT_CONTROLLER);
    // }

    // Validación de sesión de usuario, descomentar si requerida
    // if (!Auth::validate()) {
    //  Flasher::new('Debes iniciar sesión primero.', 'danger');
    //  Redirect::to('login');
    // }

    // Ejecutar la funcionalidad del Controller padre
    parent::__construct();
  }

  function index()
  {
    $albums = albumsModel::all();
    $this->setTitle('Listado de Albums');
    $this->addToData('msg', 'Bienvenido al controlador de "albums", se ha creado con éxito si ves este mensaje.');
    $this->setEngine('bee');
    $this->addToData('albums', $albums);
    $this->setView('albums'); // por defecto es index
    $this->render();
  }

  function ver($id)
  {
    $this->setTitle('Reemplazar título');
    $this->setEngine('bee');
    $this->setView('ver'); // por defecto es index
    $this->render();
  }

  function agregar()
  {
    $this->setTitle('Reemplazar título');
    $this->setEngine('bee');
    $this->setView('agregar'); // por defecto es index
    $this->render();
  }

  function post_agregar()
  {
    try {
      if (!check_posted_data(['title', 'artist', 'genre', 'release_date'], $_POST)) {
        throw new Exception('Por favor, completar el formulario.');
      }

      if (!Csrf::validate($_POST['csrf'])) {
        throw new Exception(get_bee_message(0));
      }

      array_map('sanitize_input', $_POST);

      $title = $_POST['title'];
      $artist = $_POST['artist'];
      $genre = $_POST['genre'];
      $release_date = $_POST['release_date'];

      $sql = 'SELECT * FROM albums WHERE title = :title OR artist = :artist';
      if (albumsModel::query($sql, ['title' => $title, 'artist' => $artist])) {
        throw new Exception('Ya existe un album registrado con el mismo titulo o artista');
      }

      if (strlen($title) < 3) {
        throw new Exception('El titulo debe tener mínimo 3 caracteres');
      }

      $album =
        [
          'title' => $title,
          'artist' => $artist,
          'genre' => $genre,
          'release_date' => $release_date,
          'created_at' => now()
        ];

      if (!$id = albumsModel::insertOne($album)) {
        throw new Exception('Hubo un error, intenta nuevamente.');
      }

      Flasher::success(sprintf('Nuevo album <b>%s</b> agregado con éxito', $album['title']));
      Redirect::back();
    } catch (Exception $e) {
      Flasher::error($e->getMessage());
      Redirect::back();
    }
  }

  function editar($id)
  {
    $this->setTitle('Reemplazar título');
    $this->setEngine('bee');
    $this->setView('editar'); // por defecto es index
    $this->render();
  }

  function post_editar($id)
  {

    try {
      if (!check_posted_data(['id', 'title', 'artist', 'genre', 'release_date'], $_POST)) {
        throw new Exception('Datos incompletos');
      }
      if (!Csrf::validate($_POST['csrf'])) {
        throw new Exception(get_bee_message(0));
      }
      $id = $_POST['id'];

      $album = [
        'title' => $_POST['title'],
        'artist' => $_POST['artist'],
        'genre' => $_POST['genre'],
        'release_date' => $_POST['release_date'],
        'updated_at' => now()
      ];

      if (!albumsModel::update_by_id($id, $album)) {
        throw new Exception('No se pudo actualizar');
      }
      Flasher::success('Álbum actualizado');
      Redirect::to('albums');
    } catch (Exception $e) {
      Flasher::error($e->getMessage());
      Redirect::back();
    }
  }

  function borrar($id)
  {
    try {
      if (!$album = albumsModel::by_id($id)) {
        throw new Exception('No existe ese album.');
      }
      if (!albumsModel::delete_by_id($id)) {
        throw new Exception('Error al borrar el álbum.');
      }
    Flasher::success('Album borrado');
    Redirect::back();
    } catch (Exception $e) {
      Flasher::error($e->getMessage());
      Redirect::back();
    }
  }
}
