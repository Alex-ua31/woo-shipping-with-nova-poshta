<?php

namespace plugins\NovaPoshta\classes;

/**
 * Class Warehouse
 * @package plugins\NovaPoshta\classes
 */
class Warehouse extends Location
{

    /**
     * @return string
     */
    public static function table()
    {
        return NP()->db->prefix . 'nova_poshta_warehouse';
    }

    /**
     * @param string $cityRef
     * @return Warehouse[]
     */
    public static function findByCityRef($cityRef)
    {
        $query = NP()->db->prepare("SELECT * FROM " . self::table() . " WHERE `city_ref` = '%s'", $cityRef);
        $result = NP()->db->get_results($query);
        $cities = array();
        foreach ($result as $items) {
            $cities[] = new Warehouse($items);
        }
        return $cities;
    }

    /**
     * @param $cityRef
     * @return array
     */
    public static function getWarehousesListByCityRef($cityRef)
    {
        $result = array('' => __('Choose an option', NOVA_POSHTA_DOMAIN));
        $warehouses = self::findByCityRef($cityRef);
        /** @var Warehouse $warehouse */
        foreach ($warehouses as $warehouse) {

            $result[$warehouse->ref] = $warehouse->description;
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function ajaxGetWarehousesListByCityRef()
    {
        $cityRef = $_POST['city_ref'];
        echo json_encode(self::getWarehousesListByCityRef($cityRef));
        exit;
    }

    public static function ajaxGetWarehousesBySuggestion()
    {
        $cityRef = $_POST['city_ref'];
        $name = $_POST['name'];
        $warehouses = self::findByCityRefAndName($name, $cityRef);
        foreach ($warehouses as $warehouse) {
            $warehouse->getRef();
            $warehouse->getDescription();
        }
        echo json_encode($warehouses);
        exit;
    }

    /**
     * @param $name
     * @param $cityRef
     * @return Warehouse[]
     */
    private static function findByCityRefAndName($name, $cityRef)
    {
        $query = "SELECT * FROM " . self::table() . " WHERE `city_ref` = '" . $cityRef . "' AND (`description` LIKE CONCAT('%', '" . $name . "', '%') OR `description_ru` LIKE CONCAT('%', '" . $name . "', '%'))";
        return self::findByQuery($query);
    }
}