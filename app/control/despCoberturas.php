<?php

require_once '../clases/coberturas.php';
require_once '../clases/inputValidate.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validate = new Input();
    //*Se instancia la clase para la validación de campos

    $coberturas = new Cobertura();

    switch($_REQUEST['action']){
        case 'CargarMantenedorCoberturas';
            echo $coberturas->cargarCoberturas();
        break;
        case 'CargarCoberturasCarta';
            echo $coberturas->cargarCoberturasCarta();
        break;
        case 'EliminarCobertura':
            if(isset($_SESSION['user'][1]) && $_SESSION['user'][1] != 'NULL'){
                $coberturas->setIdCobertura($_POST['id']);
                echo $coberturas->EliminarCobertura($_SESSION['user'][1]);
            }
        break;
        case 'CargarModalCobertura':
            $coberturas->setIdCobertura($_POST['id']);
            echo $coberturas->ObtenerInformacionCobertura();
        break;
        case 'ActualizarDatosCobertura':
        // *Se verifican los datos del lado del servidor
        if($validate->check(['nombre', 'descripcion', 'precio', 'estado', 'indice', 'stock', 'unidadStock', 'uso', 'unidadUso', 'minima'], $_REQUEST)){
            $id = $_POST['id'];
            $nombre = $validate->str($_POST['nombre'], '100', '3');
            $descripcion = $validate->str($_POST['descripcion'], '1000', '3');
            $precio = $validate->int($_POST['precio'], 1000000, 0);
            $idIndice = $_POST['indice'];
            $idEstado = $_POST['estado'];
            // $stock = $validate->int($_POST['stock'], 1000, 1);
            // $uso = $validate->int($_POST['uso'], 10000, 1);
            // $minima = $validate->int($_POST['minima'], 1000, 1);

            $stock = $_POST['stock'];
            $uso = $_POST['uso'];
            $minima = $_POST['minima'];

            // *Si no se agregó una imagen se ingresa el texto misma que en el procedimiento deja por defecto la imagen ya almacenada
            if(empty($_FILES["imagenUrl"]["name"])){
                $fileText = 'Misma';
            }else{
                // *Se guarda la imagen en la carpeta uploads
                $target_dir = "../../public/uploads/";
                $target_file = $target_dir . basename($_FILES["imagenUrl"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                //*Comprueba si la imagen es real
                $check = getimagesize($_FILES["imagenUrl"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
                //*Comprueba el formato de la imagen
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                    return '2';
                } else {
                    move_uploaded_file($_FILES["imagenUrl"]["tmp_name"], $target_file);
                    $fileText = basename($_FILES['imagenUrl']['name']);
                }
            }

            // *Se setean los datos en la clase coberturas
            $coberturas->setIdCobertura($id);
            $coberturas->setNombre($nombre);
            $coberturas->setDescripcion($descripcion);
            $coberturas->setIdIndice($idIndice);
            $coberturas->setPrecioAdicional($precio);
            $coberturas->setIdEstado($idEstado);
            $coberturas->setImgUrl($fileText);
            $coberturas->setCantidadStock($stock);
            $coberturas->setUnidadStock($_POST['unidadStock']);
            $coberturas->setCantidadUso($uso);
            $coberturas->setCantidadMinima($minima);

            echo $coberturas->actualizarDatos($_SESSION['user'][1]);
        }
        break;

        case 'IngresarCobertura':
        // *Se comprueba que se ingresen los datos
        if($validate->check(['nombre', 'descripcion', 'precio', 'indice', 'estado', 'stock', 'unidadStock', 'uso', 'unidadUso', 'minima'], $_REQUEST)){
            $nombre = $validate->str($_POST['nombre'], '100', '3');
            $descripcion = $validate->str($_POST['descripcion'], '1000', '3');
            $precio = $validate->int($_POST['precio'], 1000000, 0);
            $idIndice = $_POST['indice'];
            $idEstado = $_POST['estado'];
            // $stock = $validate->int($_POST['stock'], 1000, 1);
            // $uso = $validate->int($_POST['uso'], 10000, 1);
            // $minima = $validate->int($_POST['minima'], 1000, 1);

            $stock = $_POST['stock'];
            $uso = $_POST['uso'];
            $minima = $_POST['minima'];

            // *Si no se selecciona una imagen se le asigna la que existe por defecto en los registros
            if(empty($_FILES["imagenUrl"]["name"])){
                $fileText = 'default_food.jpg';
            }else{
                $target_dir = "../../public/uploads/";
                $target_file = $target_dir . basename($_FILES["imagenUrl"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                //*Comprueba si la imagen es real
                $check = getimagesize($_FILES["imagenUrl"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
                //*Comprueba el formato de la imagen
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                    return '2';
                } else {
                    move_uploaded_file($_FILES["imagenUrl"]["tmp_name"], $target_file);
                    $fileText = basename($_FILES['imagenUrl']['name']);
                }
            }

            // *Se setean los datos a la clase coberturas
            $coberturas->setNombre($nombre);
            $coberturas->setDescripcion($descripcion);
            $coberturas->setPrecioAdicional($precio);
            $coberturas->setIdIndice($idIndice);
            $coberturas->setIdEstado($idEstado);
            $coberturas->setImgUrl($fileText);
            $coberturas->setCantidadStock($stock);
            $coberturas->setUnidadStock($_POST['unidadStock']);
            $coberturas->setCantidadUso($uso);
            $coberturas->setCantidadMinima($minima);

            echo $coberturas->ingresarCoberturas($_SESSION['user'][1]);
        }
        break; 

        // *Se obtienen los datos de la tabla coberturas en BD para cargarlos en el checkbox
        case 'CargarChecboxCoberturas';
            echo $coberturas->cargarCoberturas();
        break;

        // *Comprueba que la cobertura a eliminar no esté vinculada a un tipo de cobertura
        case 'ComprobarVinculacionCoberturas':
            $coberturas->setIdCobertura($_POST['id']);
            echo $coberturas->comprobarVinculacionCoberturas();
        break;
    }
}