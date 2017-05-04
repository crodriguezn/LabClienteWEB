<?php
/*
 * @autor Ivette Mateo Washbrum, Katherine Gallegos Carrillo, Yessenia Vargas Matute, Carlos Luis Rodriguez Nieto
 * @date 30-abr-2017
 * @time 11:17:18
 */

require_once '../../system.php';
require_once BASECLASS . 'catalogo.php';
require_once BASECLASS . 'catalogo_tipo.php';
require_once BASECLASS . 'producto.php';
require_once BASECLASS . 'producto_tipo.php';

$mProductoTipo = new mProducto_Tipo();
$mProducto = new mProducto();
$mProductoTipo->filter($eProductoTipos/* REF */);
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="es">
    <head>
        <title>100% Natural y Saludable::Bienvenidos</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Carlos Rodriguez">
        <meta name="description" content="CV">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="../../resources/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Optional theme -->
        <link rel="stylesheet" href="../../resources/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

        <link rel="stylesheet" type="text/css" href="../../resources/css/style.css">

        <script type="text/javascript" src="../../resources/js/jquery-3.2.1.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script type="text/javascript" src="../../resources/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script type="text/javascript" src="../js/productos.js"></script>
    </head>
    <body>
        <header class="cabecera">
            <div class="container">
                <div class="row header">
                    <div class="col-sm-4" id="headerlogo">
                        <!--logo-->
                        <a href="javascript:void(0);"><img src="../../resources/img/logo.png"></a>
                        <!--//fin logo--> 
                    </div>

                    <div class="col-sm-8" id="headertopmenu">
                        <nav class="menu">
                            <ul>
                                <li><a href="index.php">Inicio</a></li>
                                <li><a href="productos.php">Productos</a></li>
                                <li><a href="guia.php">Guía</a></li>
                                <li><a href="administrar_productos.php">Administrar Productos</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <nav class="text-left">
            <div class="container ">
                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li><a href="index.php">Inicio</a></li>
                        <li><a href="productos.php">Productos</a></li>
                    </ul>
                </div>

            </div>
        </nav>
        <div class="container">
            <div class="container-producto">
                <div class="row">
                    <div class="col-sm-12">
                        <h1>Productos</h1>
                    </div>
                    <?php
                    /* @var $eProductoTipo eProductoTipo  */
                    foreach ($eProductoTipos as $eProductoTipo) {
                        ?>
                        <div class="col-sm-12">
                            <div class="panel panel-primary">
                                <div class="pull-right"> 
                                    <button class="btn btn-default eye-close" _id="<?php echo $eProductoTipo->id ?>"><span class="glyphicon glyphicon-eye-close"></span></button>&nbsp;
                                    <button class="btn btn-default eye-open" _id="<?php echo $eProductoTipo->id ?>"><span class="glyphicon glyphicon-eye-open"></span></button>
                                    <span class="clear"></span>
                                </div>
                                <div class="panel-heading">
                                    <h4><?php echo $eProductoTipo->name ?></h4>
                                </div>
                                <div class="panel-body pnl-prodcuto" id="<?php echo $eProductoTipo->id ?>">
                                    <?php
                                    $filterProducto = new filterProducto();
                                    $filterProducto->id_product_type = $eProductoTipo->id;
                                    $mProducto->filter($filterProducto, $eProductos, $eProductoTipos);
                                    /* @var $eProducto eProducto  */
                                    foreach ($eProductos as $eProducto) {
                                        ?>
                                        <div class="view-product col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <?php
                                            $img = "../../resources/img/nologo.png";
                                            //print_r($img);
                                            //print_r((!is_null($eProducto->url_picture) && file_exists("../../".$eProducto->url_picture)));
                                            if ((!is_null($eProducto->url_picture) && !empty($eProducto->url_picture)) && file_exists("../../" . $eProducto->url_picture)) {
                                                $img = "../../" . $eProducto->url_picture;
                                            }
                                            ?>
                                            <img src="<?php echo $img; ?>" border="0">
                                            <h4 class="name"><?php echo $eProducto->name ?></h4>
                                            <p class="description"><?php echo $eProducto->description ?></p>
                                            <p class="presentation"><?php echo $eProducto->presentation ?></p>
                                            <p class="code">INVIMA: <span><?php echo $eProducto->code ?></span></p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <div class="container">
            <footer class="pie">
                © 2015 - 2018 . Developer by Ivette Mateo Washbrum, Katherine Gallegos Carrillo, Yessenia Vargas Matute, Carlos Luis Rodriguez Nieto. All rights reserved.
            </footer>
        </div>

    </body>
</html>
