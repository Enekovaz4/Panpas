<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/






Route::get('/', 'IndexController@getRecetasRanking')->name('index');

//Con Verificación por Email
Auth::routes(['verify' => true]);

//*******************************************************************************
//  :: Autenticación por Red(es) Social(es) - ini ::

//Redirigiendo al usuario a la página de autenticación del proveedor del servicio
Route::get('/auth/{provider}', 'Auth\LoginController@redirectToProvider')
    ->where('provider','twitter|facebook|github')
    ->name('provider.login');

//Obteniendo la información del usuario desde el proveedor del servicio
Route::get('/auth/{provider}/callback', 'Auth\LoginController@handleProviderCallback')
    ->where('provider','twitter|facebook|github')
    ->name('provider.callback');

//  :: Autenticación por Red(es) Social(es) - fin ::
//*******************************************************************************

//Landing Page
//  >> guarda los datos del formulario de la landing page en la DDBB
Route::post('/enviarDatosContacto', 'ContactoController@store');

//Panel donde se muestra la Receta
Route::get ('/receta/{id}/{toast?}', 'RecetaController@mostrar')->name('receta');
Route::post ('/insertarComentario', 'RecetaController@insertarComentario');

//Panel de Usuarios
Route::get('/users/eliminarCuenta', 'UserPanelController@eliminarCuenta')
    ->name('user_eliminar_cuenta');

Route::get('/users/{username?}', 'UserPanelController@index')
    ->name('user_panel_index');

Route::get('/logout', 'UserPanelController@logout')
    ->name('user_panel_logout');

Route::get('/recetas/{toast?}', 'UserPanelController@listaRecetas')
    ->name('user_recetas');

Route::get('/seguidos/{columna?}/{orden?}', 'UserPanelController@seguidos')
    ->name('user_seguidos');

Route::get('/seguidores', 'UserPanelController@seguidores')
    ->name('user_seguidores');

Route::get('/usuarios/{columna?}/{orden?}', 'UserPanelController@usuarios')
    ->name('user_usuarios');
Route::post('/buscarUsuario', 'UserPanelController@buscarUsuario')
    ->name('user_buscarUsuario');

Route::post('/buscarReceta', 'UserPanelController@buscarReceta')
    ->name('user_buscarReceta');



Route::post('/insertarReceta', 'RecetaController@insertarReceta')
    ->name('insertar_receta');




//Panel Público de Usuario
Route::get('/{username}', 'UserPanelController@perfil')
    ->name('user_perfil');

//rutas de usuario PRIVADAS
Route::post('/user/guardarFotoPerfil', 'UserPerfilController@guardarFotoPerfil');
Route::post('/user/guardarFotoPerfilURL', 'UserPerfilController@guardarFotoPerfilURL');
Route::post('/user/actualizarDatos', 'UserPerfilController@actualizarDatos');

//FOLLOW SYSTEM routes
Route::get('/unfollow/{id}', 'FollowController@unfollow')
    ->name('follow_unfollow');
Route::get('/follow/{id}', 'FollowController@follow')
    ->name('follow_follow');

//FAVORITOS SYSTEM routes
Route::get('/fav/{id}', 'RecetaController@insertarFavoritos')
    ->name('fav_insertarFavoritos');
Route::get('/unfav/{id}', 'RecetaController@eliminarFavoritos')
    ->name('fav_eliminarFavoritos');


//Panel Admin
Route::get('/admin/dashboard', 'AdminPanelController@index')
    ->middleware('can:isAdmin')//solo se puede acceder si es Admin
    ->name('admin_pnl_index');
Route::get('/admin/dashboard/get-tots', 'AdminPanelController@getTots')
    ->middleware('can:isAdmin')
    ->name('admin_pnl_index_tots');
Route::get('/admin/dashboard/last-register', 'AdminPanelController@lastRegisterUsersRecipes')
    ->middleware('can:isAdmin')
    ->name('admin_pnl_index_last_register');
Route::post('/admin/dashboard/search-recipes-date-range', 'AdminPanelController@searchRecipesXDateRange')
    ->middleware('can:isAdmin')
    ->name('admin_pnl_index_recipes-date-range');
Route::get('/admin/dashboard/search-users-world', 'AdminPanelController@searchUsersWorld')
    ->middleware('can:isAdmin')
    ->name('admin_pnl_index_users-world');
Route::get('/admin/dashboard/search-recipes-x-categ', 'AdminPanelController@searchRecipesXCateg')
    ->middleware('can:isAdmin')
    ->name('admin_pnl_index_recipes-x-categ');

//Para aceptar URLs de todo tipo dentro de  este controlador
//  >> establecido para aceptar peticiones GET a las rutas
//  relacionadas con los componentes a cargar en él
Route::get('/admin/{path}', 'AdminPanelController@index')
    ->where( 'path', '([A-z\d\-\/_.]+)?' )
    ->middleware('can:isAdmin');
/**
 * ¡¡ATENCIÓN!!
 * La siguiente expresión regular " ([A-z\d-\/_.]+)? ", por los requerimientos de Heroku
 * sobre "PCRE - Perl Compatible Regular Expressions", no es totalmente válida y produce
 * una excepción (aunque en LOCAL funcione correctamente).
 * El ERROR se produce por el carácter "-" (hyphen) que se exige debe ser "escapado" (\-)
 * o puesto en último lugar de la expresión siempre que no sirva para limitar un rango.
 * En esta ocasión se opta por escaparlo:
 *      '([A-z\d\-\/_.]+)?'
 */

//==================================================
Route::get('/home', 'HomeController@index')
    ->name('home');




//AJAX
Route::post ('/ajax/usuarios', 'AjaxController@updateUsers');
Route::post ('/ajax/getUsuarios', 'AjaxController@getUsuarios');
Route::post ('/ajax/getSearchUsuarios/{termino}', 'AjaxController@getSearchUsuarios');
Route::post ('/ajax/follow/{id}', 'AjaxController@follow');
Route::post ('/ajax/unfollow/{id}', 'AjaxController@unfollow');
Route::post ('/ajax/getSearchRecetas/{termino}', 'AjaxController@getSearchRecetas');
Route::post ('/ajax/misRecetas', 'AjaxController@getMisRecetas');
Route::post ('/ajax/getRecetas', 'AjaxController@getRecetas');
Route::post ('/ajax/fav/{id}', 'AjaxController@fav');
Route::post ('/ajax/unfav/{id}', 'AjaxController@unfav');







// [API]recoger datos

Route::get ('/api/usuarios', 'ApiController@getUsuarios'); 	//json de todos los usuarios registrados
Route::get ('/api/perfiles', 'ApiController@getPerfiles');	//json de todos los perfiles disponibles
Route::get ('/api/recetas', 'ApiController@getRecetas');	//json de todas las recetas disponibles
Route::get ('/api/ingredientes', 'ApiController@getIngredientes');	//json de todos los ingredinetes disponibles
Route::get ('/api/panaderias', 'ApiController@getPanaderias');	//json de todas las panaderias disponibles













































//PARA HACER FILTRO


/*

Route::post('/admin/editarUsuario', 'UserController@editarUsuario');	//modifica los datos de un usuario en la DDBB
Route::post('/admin/borrarUsuario', 'UserController@borrarUsuario');	//borra un usuario de la DDBB

*/






//Rutas de la parte PÚBLICA
Route::get ('/user/perfilPublico/{id}', 'UserPerfilController@mostrarPerfilPublico'); //mostrar perfil del usuario con información pública
Route::get ('/user/index', 'UserPerfilController@index'); //mostrar perfil del usuario con información pública




//prueba carga de foto de perfil (Borrar al terminar)
//se carga desde la modificación del perfil privado
Route::get('/prueba', 'UserPerfilController@prueba');




