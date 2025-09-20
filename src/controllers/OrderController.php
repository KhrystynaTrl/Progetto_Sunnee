<?php

namespace App\Controllers;

use App\config\Container;
use App\Models\Order;
use App\Models\Product;
use Core\Response;
use Exception;

class OrderController {

    private Order $order;
    private Product $product;

    public function __construct(){
        $conn = Container::getInstance()->make(\PDO::class);
        $this->order = new Order($conn);
        $this->product = new Product($conn);
    }

public function readAll(){
        try{
            $result = $this->order->readAll();

            if(empty($result)){
                Response::get(404, "No orders found");
            }
            Response::get(200, $result);
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function read(){
        try{
            $id = (int)$_GET['id'];

            if(empty($id)){
                Response::get(400, "Parameter id not found");
            }

            $result = $this->order->read($id);

            if(empty($result)){
                Response::get(404, "Order not found");
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

            if($this->order->create($data)){
                Response::get(201,"Order correctly created");
            } else {
                Response::get(500, "Error while creating order");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function update() {
        try{
             $data = json_decode(file_get_contents('php://input'));

            $this->checkData($data);

            if($this->order->update($data)){
                Response::get(200,"Order correctly updated");
            } else {
                Response::get(500, "Error while updating order");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function delete(){
        try{
            $id = (int)$_GET['id'];

            if(empty($id)){
                Response::get(400, "Parameter id not found");
            }

            $result = $this->order->delete($id);

            if($result){
                Response::get(200,$result);
            } else {
                Response::get(500, "Error while deleting order");
            }
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    public function search(){
        try{
            $serchData = json_decode(file_get_contents("php://input"));
            $this->parseSearchParameterDate($serchData);

            $result = $this->order->search($serchData);

            Response::get(200,$result);
        } catch(Exception $e){
            Response::get(500, $e->getMessage());
        }
    }

    private function checkData($data) {
        if (!$data) {
            Response::get(404, "Missing request object");
        }
        if (empty($data->product)) {
            Response::get(404, "Missing 'product' parameter");
        }
        if (empty($data->quantity)) {
            Response::get(404, "Missing 'quantity' parameter");
        }
        
        if (!empty($data->date_of_sale)) {
            $data->date_of_sale = $this->checkDateCustom($data->date_of_sale);
        }
        
        $productRecord = $this->product->findByName($data->product);
        
        if (empty($productRecord)) {
            Response::get(404, "Product not found");
        }
        
        $data->product_id = $productRecord['ID'] ?? null;
    }

    private function parseSearchParameterDate($searchData){
        if(!empty($searchData->dateFrom)){
            $searchData->dateFrom = $this->checkDateCustom($searchData->dateFrom);
        }
        if(!empty($searchData->dateTo)){
            $searchData->dateTo = $this->checkDateCustom($searchData->dateTo);
        }
    }

    private function checkDateCustom($date){
        $data_decode = \DateTime::createFromFormat("d/m/Y", $date);
        if (!$data_decode) {
            Response::get(400, "Invalid date format for 'date_of_sale',required 'd/m/Y'");
        }
        return $data_decode->format("Y-m-d");
    }
}

