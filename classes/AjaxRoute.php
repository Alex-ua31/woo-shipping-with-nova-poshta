<?php

namespace plugins\NovaPoshta\classes;

/**
 * Class AjaxRoute
 * @package plugins\NovaPoshta\classes
 */
class AjaxRoute
{
    const GET_CITIES_ROUTE = 'nova_poshta_get_cities_by_area';
    const GET_WAREHOUSES_ROUTE = 'nova_poshta_get_warehouses_by_city';

    public static $handlers = array(
        self::GET_CITIES_ROUTE => array(City::class, 'ajaxGetCitiesListByAreaRef'),
        self::GET_WAREHOUSES_ROUTE => array(Warehouse::class, 'ajaxGetWarehousesListByCityRef'),
    );

    public static function init()
    {
        foreach (self::$handlers as $key => $handler) {
            $ajaxRoute = new self($key, $handler);
            $ajaxRoute->handleRequest();
        }
    }

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $handler;

    /**
     * AjaxRoute constructor.
     * @param string $route
     * @param string $handler
     */
    public function __construct($route, $handler)
    {
        $this->route = $route;
        $this->handler = $handler;
    }

    /**
     * Handle ajax request
     */
    public function handleRequest()
    {
        add_action('wp_ajax_' . $this->route, $this->handler);
        add_action('wp_nopriv_ajax_' . $this->route, $this->handler);

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == $this->route) {
            do_action('wp_ajax_' . $_REQUEST['action']);
            do_action('wp_ajax_nopriv_' . $_REQUEST['action']);
        }
    }
}