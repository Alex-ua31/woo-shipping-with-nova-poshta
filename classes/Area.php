<?php

namespace plugins\NovaPoshta\classes;

/**
 * Class Area
 * @package plugins\NovaPoshta\classes
 */
class Area extends Location
{

    /**
     * @return string
     */
    public static function table()
    {
        return NP()->db->prefix . 'nova_poshta_area';
    }

    /**
     * @return array
     */
    public static function getAreasList()
    {
        $result = array('' => __('Choose an option', NOVA_POSHTA_DOMAIN));
        $areas = Area::findAll();
        /** @var Area $area */
        foreach ($areas as $area) {
            $result[$area->ref] = $area->description;
        }
        return $result;
    }

    public static function ajaxGetAreasBySuggestion()
    {
        $name = $_POST['name'];
        $areas = Area::findByName($name);
        foreach ($areas as & $area) {
            $area->getDescription();
            $area->getRef();
        }
        echo json_encode($areas);
        exit;
    }
}