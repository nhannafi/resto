<?php


class OrderDetailModel
{
    public function find($id)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("SELECT *
								FROM OrderDetails 
								WHERE Id = :id");

        $query->execute([
            "id" => $id,
        ]) ;


        return $query->fetch() ;
    }

    public function findBasketByOrder($idOrder)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("SELECT OrderDetails.Id, QuantityOrdered, PriceEach, Title, Image  
								FROM OrderDetails 
								INNER JOIN Menus 
								ON Menus.Id = OrderDetails.Menus_Id
								WHERE Orders_Id = :idOrder");

        $query->execute([
            "idOrder" => $idOrder,
        ]) ;


        return $query->fetchAll() ;
    }

    public function addOrCreateToMenu($idOrder, $idMenu, $quantity, $priceEach)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("INSERT INTO OrderDetails
								(`Orders_Id`, `Menus_Id`, `QuantityOrdered`, `PriceEach`)
                                VALUES (:idOrder, :idMenu, :quantity, :priceEach)
                                ON DUPLICATE KEY UPDATE 
                                QuantityOrdered = QuantityOrdered + :quantity");

        $query->execute([
            "idOrder" => $idOrder,
            "idMenu" => $idMenu,
            "quantity" => $quantity,
            "priceEach" => $priceEach,
        ]) ;
    }


    public function setOrCreateToMenu($idOrder, $idMenu, $quantity, $priceEach)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("INSERT INTO OrderDetails
								(`Orders_Id`, `Menus_Id`, `QuantityOrdered`, `PriceEach`)
                                VALUES (:idOrder, :idMenu, :quantity, :priceEach)
                                ON DUPLICATE KEY UPDATE 
                                QuantityOrdered = :quantity");

        $query->execute([
            "idOrder" => $idOrder,
            "idMenu" => $idMenu,
            "quantity" => $quantity,
            "priceEach" => $priceEach,
        ]) ;
    }

    public function updateQuantity($id, $quantity)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("UPDATE OrderDetails
								SET QuantityOrdered = :quantity
								WHERE Id = :id");

        $query->execute([
            "id" => $id,
            "quantity" => $quantity,
        ]) ;

    }

    public function removeByOrder($idOrder)
    {

        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("DELETE FROM OrderDetails
								WHERE Orders_Id = :idOrder");

        $query->execute([
            "idOrder" => $idOrder,
        ]) ;
    }

    public function remove($id)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("DELETE FROM OrderDetails
								WHERE Id = :id");

        $query->execute([
            "id" => $id,
        ]) ;
    }
}