<?php

namespace plugins\NovaPoshta\classes;

use NovaPoshta;
use plugins\NovaPoshta\classes\base\Base;
use stdClass;

/**
 * Class Location
 * @package plugins\NovaPoshta\classes
 *
 * @property stdClass content
 * @property string description
 * @property string ref
 * @property string locale
 */
class Area extends Base
{
    const KEY_REGION = 'nova_poshta_region';
    const KEY_CITY = 'nova_poshta_city';
    const KEY_WAREHOUSE = 'nova_poshta_warehouse';

    const BILLING = 'billing';
    const SHIPPING = 'shipping';

    /**
     * @return string
     */
    public static function table()
    {
        _doing_it_wrong("Area table", "You have to override this method in child classes", "2.1.0");
        return '';
    }

    /**
     * @return string
     */
    public static function key()
    {
        _doing_it_wrong("Area Type", "You should not call this method from abstract class", "1.0.0");
        return '';
    }

    /**
     * @param string $name
     * @param string $type
     * @return string
     */
    protected static function _key($name, $type)
    {
        return $type ? $type . '_' . $name : $name;
    }

    /**
     * Location constructor.
     * @param $ref
     */
    public function __construct($ref)
    {
        if (is_string($ref)) {
            $this->ref = $ref;
        } else {
            $this->content = json_decode(json_encode($ref));
        }
    }

    /**
     * @return mixed
     */
    protected function getLocale()
    {
        return get_locale();
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $table = static::table();
        $query = NP()->db->prepare("SELECT * FROM $table WHERE ref = %s", $this->ref);
        return NP()->db->get_row($query);
    }

    /**
     * @return string
     */
    protected function getDescription()
    {
        return (($this->locale == NovaPoshta::LOCALE_RU) && $this->content->description_ru)
            ? $this->content->description_ru
            : $this->content->description;
    }

    /**
     * @return string
     */
    protected function getRef()
    {
        return $this->content->ref;
    }

}