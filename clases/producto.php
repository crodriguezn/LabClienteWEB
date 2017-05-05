<?php

/*
 * @autor Ivette Mateo Washbrum, Katherine Gallegos Carrillo, Yessenia Vargas Matute, Carlos Luis Rodriguez Nieto
 * @date 30-abr-2017
 * @time 17:44:20
 * Objetivo: Clase Producto, contiene atributos y metodos de los Productos
 */

include_once("MY_Model.php");
include_once("MY_Entity.php");
/* 
 * clase: mProducto
 * Descripción: permite la creación de objetos de tipo eProducto con la opciones de crear, editar, listar y eliminar en la base de datos
 */
class mProducto extends MY_Model
{
    protected $table = 'product';

    function __construct() 
    {
        parent::__construct();
        
    }
    /* 
     * función: load
     * Descripción: cargar la información de un producto
     */
    function load($value, $by = 'id', $except_value = '', $except_by = 'id')
    {
        $row = parent::load($value, $by, $except_value, $except_by);
        
        $eProducto = new eProducto();
        $eProducto->parseRow($row);
        
        return $eProducto; //me retorna una entidad 
    }
    
	/* 
     * función: genId
     * Descripción: obtener identificador del registro
     */
    function genId() 
    {
        return parent::genId();
    }
	
	/* 
     * función: save
     * Descripción: almacenamiento de cambios en la base de datos
     */
    function save(eProducto &$eProducto)
    {
        try
        {
			//Si el identificador está vacio, es un nuevo producto
            if (empty($eProducto->id)) 
            {
                $eProducto->id = $this->genId();
                $this->_save($this->_insert($eProducto->toData()));
            }
            else // Caso contrario se trata de un proceso de actualización de información
            {
                $this->_save($this->_update($eProducto->toData(TRUE), $eProducto->id));
            }
        }
        catch (Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
    }
    
    /* 
     * función: delete
     * Descripción: eliminar un producto de la base de datos
     */
    function delete($id)
    {
        try
        {
            $this->_save($this->_delete($id));
        }
        catch (Exception $ex)
        {
            throw new Exception($ex->getMessage());
        }
    }

    /* 
     * función: _insert
     * @param $table String 
     * @param $keys Array 
     * @param $values Array 
     * Descripción, permite insertar un registro a la base de datos
     */
    function _insert($arrData)
    {
        $keys = array_keys($arrData);
        $values = array_values($arrData);
        
        foreach ($keys as $num => $key)
        {
            
            if($key == 'name' || $key == 'description' || $key == 'presentation' || $key == 'code' || $key == 'url_picture')
            {
                $values[$num] = "\"".($values[$num])."\"";
            }
        }
       
        return "INSERT INTO ".$this->table." (".implode(", ", $keys).") VALUES (".implode(', ', $values).")";
    }
    
    /*
     * función: _update
     * @param $arrData Array 
     * @param $value String 
     * @param $by String 
     * Descripción, permite actualizar un registro a la base de datos
     */
	 
    function _update( $values, $id)
    {
        foreach ($values as $key => $val)
        {
            if($key == 'name' || $key == 'description' || $key == 'presentation' || $key == 'code' || $key == 'url_picture')
            {
                //$val = "\"".($val)."\"";
                $valstr[] = sprintf('%s = "%s"', $key, $val);
            }
            else
            {
                $valstr[] = sprintf('%s = %s', $key, $val);
            }
        }

        $sql = "UPDATE ".$this->table." SET ".implode(', ', $valstr);

        $sql .= " WHERE id = ".$id;
        return $sql;
    }
	
    /* 
     * función: _delete
     * @param $id String  
     * Descripción, permite eliminar un registro a la base de datos
     */
	 //array('id' =>1)
    function _delete( $id)
    {
        $sql = "DELETE FROM ".$this->table." WHERE id = ".$id;
        return $sql;
    }

	/* 
     * función: filter 
     * Descripción: obtener un listado de productos dado un filtro específico
     */
    public function filter(filterProducto $filter, &$eProductos, &$eProductoTipos )
    {
        $eProductos = array();
        $eProductoTipos = array();
        
        $select_producto = $this->buildSelectFields('p_', 'p', $this->table);
        $select_producto_type = (is_null($filter->id_product_type)) ? NULL :$this->buildSelectFields('pt_', 'pt', 'product_type');
        if (is_null($filter->id_product_type))
        {
            $select = $select_producto;
        }
        else
        {
            $select = ($select_producto.','.$select_producto_type);
        }
        
        $sql = "SELECT
                    ".($select)."
                FROM ".( $this->table )." AS p
                    ".(is_null($filter->id_product_type) ? "":" INNER JOIN product_type AS pt ON pt.id = p.id_product_type ")."
                    ".(is_null($filter->id_catalog) ? "":" INNER JOIN catalog AS c ON c.id = p.id_catalog ")."
                WHERE
                    1=1
                " .( is_null($filter->id_product_type) ? "":" AND p.id_product_type = $filter->id_product_type "). "
                " .( is_null($filter->id_catalog) ? "":" AND p.id_catalog = $filter->id_catalog "). "
                " .( is_null($filter->limit) || is_null($filter->offset) ? '' : " LIMIT ".( $filter->limit )." OFFSET ".( $filter->offset )." " ) . "
                ";
        $queryR = mysql_query($sql);
        
        while ($row = mysql_fetch_assoc($queryR))  
        {

            $eProducto = new eProducto();

            $eProducto->parseRow($row,'p_');

            $eProductos[] = $eProducto;

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
 * clase: eProducto
 * Descripción: permite la definición de las características de un 'producto'
 */
class eProducto extends MY_Entity
{
    public $id_product_type; //tipo de producto
    public $id_catalog; //catálogo al que pertenece
    public $name; // nombre del producto
    public $description; // descripcion del producto
    public $presentation; // forma de presentacion del producto
    public $code; //código del producto
    public $url_picture; //imagen


    function __construct($useDefault = TRUE)
    {
        parent::__construct($useDefault);
        
        if( $useDefault )
        {
            $this->id_product_type  = 0;
            $this->id_catalog       = 0;
            $this->name             = '';
            $this->description      = NULL;
            $this->presentation     = NULL;
            $this->code             = NULL;
            $this->url_picture      = NULL;
        }
    }
}
/* 
 * clase: filterProducto
 * Descripción: auxiliar para definir el filtro de búsqueda de un producto
 */
class filterProducto extends MY_Entity_Filter
{
    public $id_product_type; // Por tipo de producto
    public $id_catalog; //Por catálogo al que pertenece
    
    public function __construct()
    {
        parent::__construct();
        $this->id_product_type  = NULL;
        $this->id_catalog       = NULL;
    }
    
}
