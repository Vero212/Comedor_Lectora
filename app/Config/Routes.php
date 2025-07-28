<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home'); // Será reemplazado por 'Ingreso'
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false); // Equivalente a $route['translate_uri_dashes']
$routes->set404Override(); // Equivalente a $route['404_override']
// The Auto Routing (Legacy) is very dangerous. It is disabled by default.
// You might want to set it to true for production environments but
// POST requests will be sent to the controller's 'index' method.
//
// $routes->setAutoRoute(false); // Asegúrate de que esto esté en 'false' o no esté comentado si no usas auto-routing

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
$routes->post('ingreso/validar', 'Ingreso::validar');
$routes->get('ingreso/generarTicket/(:num)', 'Ingreso::generarTicket/$1');
// We get a performance increase if we specify the default route since we don't have to scan directories.
$routes->get('/', 'Ingreso::index'); // Ruta por defecto para la URL base

/* Rutas que tenías en CI3 */
$routes->get('legajos/pagina/(:num)', 'Legajos::index/$1'); // Cuando no sea la primera página
$routes->get('legajos/pagina', 'Legajos::index');           // Cuando sea la primera página
// Si tu controlador Legajos tiene un método por defecto (ej. index)
// y quieres que ambas rutas vayan a ese método, puedes simplificarlo.

// O si siempre esperas un número, aunque pueda ser 1, podrías considerar esto:
// $routes->get('legajos/pagina/(:num)?', 'Legajos::index/$1');
// Con esta última, el '(:num)?' hace que el segmento numérico sea opcional,
// y si no se proporciona, Legajos::index() se llamaría sin un argumento.
// Tendrías que manejar el valor predeterminado del parámetro en tu método `index` del controlador `Legajos`.

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to load those from other files. To do that you may call a
 * RouteCollection method here.
 *
 * $routes->add('api', ['namespace' => 'App\Controllers\Api'], function (): void {
 * require __DIR__ . '/Api.php';
 * });
 *
 * Do Not Edit Below This Line
 * --------------------------------------------------------------------
 */