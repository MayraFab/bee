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
    $this->setTitle('Listado de Albums');
    $this->addToData('msg', 'Bienvenido al controlador de "albums", se ha creado con éxito si ves este mensaje.');
    $this->setEngine('bee');
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
      if (!check_posted_data(['tittle', 'artist', 'genre', 'release_date'], $_POST)) {
        throw new Exception('Por favor, completar el formulario.');
      }

      if (!Csrf::validate($_POST['Csrf'])) {
        throw new Exception(get_bee_message(0));
      }

      array_map('sanitize_input', $_POST);
      $tittle = $_POST['tittle'];
      $artist = $_POST['artist'];
      $genre = $_POST['genre'];
      $release_date = $_POST['release_date'];
      $errorMessage = '';
      $errors = 0;

      $sql = 'SELECT * FROM albums WHERE title = :tittle OR artist = :artist OR slug = :slug';
      if (albumsModel::query($sql, ['tittle' => $tittle, 'artist' => $artist, 'slug' => $artist])) {
        throw new Exception('Ya existe un album registrado con el mismo titulo o artista');
      }

      if (strlen($tittle) > 120) {
        $errorMessage .= 'El nombre debe ser menor a 120 caracteres.' . PHP_EOL;
        $errors++;
      }

      $albums =
        [
          'tittle' => $tittle,
          'artist' => $artist,
          'genre' => $genre,
          'release_date' => $release_date,
        ];

      if (!$id = albumsModel::insertOne($albums)) {
        throw new Exception('Hubo un error, intenta nuevamente.');
      }

      $albums = albumsModel::by_id($id);

      Flasher::success(sprintf('Nuevo album <b>%s</b> agregado con éxito', $albums['tittle']));
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

function post_editar()
{
  // Proceso de actualizado
}

function borrar($id)
{
  // Proceso de borrado
}
}