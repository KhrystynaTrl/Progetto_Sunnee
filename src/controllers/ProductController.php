<?php

namespace App\Controllers;

use App\config\Container;
use App\Models\Product;
use Exception;
use Core\Response;

class ProductController {

    private Product $product;

    public function __construct(){
        $this->product = new Product(Container::getInstance()->make(\PDO::class));
    }

    public function readAll(){
        try{
            $result = $this->product->readAll();

            if(empty($result)){
                Response::get(404, "No products found");
            }
            Response::get(200, $result);
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function read(){
        try{
            $name = $_GET['name'];
            
            if(empty($name)){
                Response::get(400, "Parameter name not found");
            }

            $result = $this->product->findByName($name);

            if(empty($result)){
                Response::get(404, "Product not found");
            }

            Response::get(200,$result);

        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function create(){
        try{
            $data = json_decode(file_get_contents('php://input'));

            $this->checkData($data);

            if($this->product->create($data)){
                Response::get(201,"Product correctly created");
            } else {
                Response::get(500, "Error while creating product");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function update() {
        try{
             $data = json_decode(file_get_contents('php://input'));

            $this->checkData($data);

            if($this->product->update($data)){
                Response::get(200,"Product correctly updated");
            } else {
                Response::get(500, "Error while updating product");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function delete(){
        try{
            $name = $_GET['name'];

            if(empty($name)){
                Response::get(400, "Parameter name not found");
            }

            $result = $this->product->delete($name);

            if($result){
                Response::get(200,$result);
            } else {
                Response::get(500, "Error while deleting product");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    private function checkData($data){
         if (!$data) {
            Response::get(404, "Missing request object");
        }
        if (empty($data->name)) {
            Response::get(404, "Missing 'name' parameter");
        }
        if (empty($data->kg_recycled)) {
            Response::get(404, "Missing 'kg_recycled' parameter");
        }
    }
}