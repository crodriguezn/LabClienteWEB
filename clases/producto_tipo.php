<?php

/*
 * @autor Ivette Mateo Washbrum, Katherine Gallegos Carrillo, Yessenia Vargas Matute, Carlos Luis Rodriguez Nieto
 * @date 30-abr-2017
 * @time 17:44:20
 */
include_once("MY_Model.php");
include_once("MY_Entity.php");

/* 
 * clase: mProducto_Tipo
 * Descripción: permite la creación de objetos de tipo eProductoTipo con la opción de listar los tipos de productos disponibles en la base de datos
 */
class mProducto_Tipo extends MY_Model
{
    protected $table = 'product_type';

    function __construct() 
    {
        parent::__construct();
        
    }
    
    public function load($value, $by = 'id', $except_value = '', $except_by = 'id')
    {
        $row = parent::load($value, $by, $except_value, $except_by);
        
        $eProductoTipo = new eProductoTipo();
        $eProductoTipo->parseRow($row);
        
        return $eProductoTipo;
    }

	/* 
     * función: filter 
     * Descripción: obtener un listado de los tipos de productos disponibles
     */
    public function filter( &$eProductoTipos )
    {
        $eProductoTipos = array();
        $select = $this->buildSelectFields('pt_', 'pt', $this->table);
        $sql = "SELECT
                    ".($select)."
                FROM ".( $this->table )." AS pt
                WHERE
                    1=1
                ";
        
        $queryR = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($queryR))  
        {
            $eProductoTipo = new eProductoTipo();

            $eProductoTipo->parseRow($row,'pt_');

            $eProductoTipos[] = $eProductoTipo;
        }
        mysql_free_result($queryR);
    }
    
    public function desconectar() 
    {
        parent::desconectar();
    }
}
/* 
 * clase: eProductoTipo
 * Descripción: permite la definición de las características de un 'tipo de producto'
 */
class eProductoTipo extends MY_Entity
{
    public $name; //Nombre del tipo de producto
    public $description; // descripción del tipo de producto
    public $isActive; //¿Está activo o no el registro?

    function __construct($useDefault = TRUE)
    {
        parent::__construct($useDefault);
        
        if( $useDefault )
        {
            $this->name             = '';
            $this->description      = '';
            $this->isActive         = '';
        }
    }
}
/* 
 * clase: filterProducto
 * Descripción: auxiliar para definir el filtro de búsqueda de un tipo producto
 */
class filterProductoTipo extends MY_Entity_Filter
{
	//No fue necesario el uso de filtro en el tipo de productos
    public function __construct()
    {
        parent::__construct();
    }
    
}