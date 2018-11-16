<?php

require '../db_connection/connection.php';

class Ingredientes extends connection {
    private $idIngrediente;
    private $nombre;
    private $cantidadStock;
    private $unidadStock;
    private $cantidadUso;
    private $unidadUso;
    private $cantidadMinima;

    public function getIdIngrediente(){
		return $this->idIngrediente;
	}

	public function setIdIngrediente($idIngrediente){
		$this->idIngrediente = $idIngrediente;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	public function getCantidadStock(){
		return $this->cantidadStock;
	}

	public function setCantidadStock($cantidadStock){
		$this->cantidadStock = $cantidadStock;
	}

	public function getUnidadStock(){
		return $this->unidadStock;
	}

	public function setUnidadStock($unidadStock){
		$this->unidadStock = $unidadStock;
	}

	public function getCantidadUso(){
		return $this->cantidadUso;
	}

	public function setCantidadUso($cantidadUso){
		$this->cantidadUso = $cantidadUso;
	}

	public function getUnidadUso(){
		return $this->unidadUso;
	}

	public function setUnidadUso($unidadUso){
		$this->unidadUso = $unidadUso;
	}

	public function getCantidadMinima(){
		return $this->cantidadMinima;
	}

	public function setCantidadMinima($cantidadMinima){
		$this->cantidadMinima = $cantidadMinima;
    }
	
	function CargarMantenedorIngredientes(){
		try{
            $db = connection::getInstance();
            $conn = $db->getConnection();
            //*Se prepara el procedimiento almacenado
            $stmt=$conn->prepare('call obtenerDatosTablaIngredientes()');
            //* Se ejecuta
            $stmt->execute();
            //* Resultados obtenidos de la consulta
            $stmt->bind_result($id, $nombre, $stock, $uso, $minimo, $stockSolo, $minimoSolo);
            $datos = array();
			// if($stmt->fetch()>0){
				while($stmt->fetch()){
					$datos[]=array(
                        "IdIngrediente"=>$id,
						"Nombre"=>$nombre,
                        "Stock"=>$stock,
						"Uso"=>$uso,
						"Minimo"=>$minimo,
						"StockTotal"=>$stockSolo,
						"MinimoStock"=>$minimoSolo
					);
				}
                return json_encode($datos);
                $stmt->free_result();
        }catch(Exception $error){
            echo 'Ha ocurrido una excepción: ', $error->getMessage(), "\n";
        }
	}

	public function EliminarIngrediente($correo){
        try{
			$db = connection::getInstance();
            $conn = $db->getConnection();
            //*Se prepara el procedimiento almacenado
			$stmt=$conn->prepare('call eliminarIngrediente(?, ?, @out_value)');
			//*Se pasan los parámetros
			$stmt->bind_param('is', $this->getIdIngrediente(), $correo);
            //* Se ejecuta
            $stmt->execute();
            //* Resultados obtenidos de la consulta
			$stmt->bind_result($result);
			if($stmt->fetch()>0){
				return $result;
			}
			else{
				return 3;
			}

			//*Se libera la respuesta en BD
			$stmt->free_result();
		}catch(Exception $error){
			echo 'Ha ocurrido una excepción: ', $error->getMessage(), "\n";
		}
    }
 
	public function ingresarIngrediente($correo){
		try{
			$db = connection::getInstance();
            $conn = $db->getConnection();
            //*Se prepara el procedimiento almacenado
			$stmt=$conn->prepare('call ingresarIngrediente(?, ?, ?, ?, ?, ?, ?, @out_value)');
			// $stmt=$conn->prepare("call ingresarIngrediente('aceite', 52, 1, 25, 1, 10, 'david_mc8@hotmail.com', @out)");
			//*Se pasan los parámetros
			$stmt->bind_param('sdidids', $this->getNombre(), $this->getCantidadStock(), $this->getUnidadStock(), $this->getCantidadUso(), $this->getUnidadUso(), $this->getCantidadMinima(), $correo);
            //* Se ejecuta
            $stmt->execute();
			//* Resultados obtenidos de la consulta
			$stmt->bind_result($result);
			if($stmt->fetch()>0){
				return $result;
			}else{
				return 3;//*error de BD
			}

			//*Se libera la respuesta en BD
			$stmt->free_result();
		}catch(Exception $error){
			echo 'Ha ocurrido una excepción: ', $error->getMessage(), "\n";
		}
	}

	public function ObtenerInformacionIngrediente(){
		try{
			$db = connection::getInstance();
            $conn = $db->getConnection();
            //*Se prepara el procedimiento almacenado
			$stmt=$conn->prepare('call obtenerDatosIngrediente(?)');
			//* Se pasa el id para obtener la información
			$stmt->bind_param('i', $this->getIdIngrediente());
            //* Se ejecuta
            $stmt->execute();
            //* Resultados obtenidos de la consulta
			$stmt->bind_result($id, $nombre, $stock, $stockUnidad, $uso, $usoUnidad, $minima);
			$datos = array();
			// if($stmt->fetch()>0){
				// *Los datos serán añadidos a un array para ser procesados y mostrados en pantalla
				while($stmt->fetch()){
					$datos[]=array(
						"Id"=>$id,
						"Nombre"=>$nombre,
						"Stock"=>$stock,
						"StockUnidad"=>$stockUnidad,
						"Uso"=>$uso,
						"UsoUnidad"=>$usoUnidad,
						"Minima"=>$minima
					);
				}

				// *Transforma el array en string
                return json_encode($datos, JSON_UNESCAPED_UNICODE);
                $stmt->free_result();
		}catch(Exception $error){
			echo 'Ha ocurrido una excepción: ', $error->getMessage(), "\n";
		}
	}

	public function actualizarIngrediente($correo){
		try{
			$db = connection::getInstance();
            $conn = $db->getConnection();
            //*Se prepara el procedimiento almacenado
			$stmt=$conn->prepare('call actualizarDatosIngredientes(?, ?, ?, ?, ?, ?, ?, ?, @out_value)');
			//*Se pasan los parámetros
			$stmt->bind_param('isdidids', $this->getIdIngrediente(), $this->getNombre(), $this->getCantidadStock(), $this->getUnidadStock(), $this->getCantidadUso(), $this->getUnidadUso(), $this->getCantidadMinima(), $correo);
            //* Se ejecuta
            $stmt->execute();
			//* Resultados obtenidos de la consulta
			$stmt->bind_result($result);
			if($stmt->fetch()>0){
				return $result;
			}else{
				return 3;//*error de BD
			}
			$stmt->free_result();
		}catch(Exception $error){
			echo 'Ha ocurrido una excepción: ', $error->getMessage(), "\n";
		}
	}
}