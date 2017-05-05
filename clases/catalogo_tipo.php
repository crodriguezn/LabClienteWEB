<?php

/*
 * @autor Ivette Mateo Washbrum, Katherine Gallegos Carrillo, Yessenia Vargas Matute, Carlos Luis Rodriguez Nieto
 * @date 30-abr-2017
 * @time 21:26:29
 */

include_once("MY_Model.php");
include_once("MY_Entity.php");
/* 
 * clase: mCatalogo_Tipo
 * Descripción: permite la creación de objetos de tipo eCatalogoTipo 
 */
class mCatalogo_Tipo extends MY_Model
{
    protected $table = 'catalog_type';
    
    function __construct() 
    {
        parent::__construct();
        
    }
    
    public function load($value, $by = 'id', $except_value = '', $except_by = 'id')
    {
        $row = parent::load($value, $by, $except_value, $except_by);
        
        $eCatalogoTipo = new eCatalogoTipo();
        $eCatalogoTipo->parseRow($row);
        
        return $eCatalogoTipo;
    }
 
    public function desconectar() 
    {
        parent::desconectar();
    }
    
}
/* 
 * clase: eCatalogoTipo
 * Descripción: permite la definición de las características de un 'tipo de catálogo'
 */
class eCatalogoTipo extends MY_Entity
{
    public $name; //Nombre del tipo de catálogo
    public $code; //Código del tipo de catálogo


    function __construct($useDefault = TRUE)
    {
        parent::__construct($useDefault);
        
        if( $useDefault )
        {
            $this->name             = '';
            $this->code             = '';
        }
    }
}
 