<?php

class OrderModel
{
    public function getUserBasketId($idUser)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("SELECT Id 
								FROM Orders
                                WHERE Users_Id = :idUser 
                                AND Status = 'basket'");

        $query->execute(["idUser" => $idUser]) ;

        $result = $query->fetch() ;
        if($result)
        {
            return $result['Id'] ;
        }
        else
        {
            $query = $pdo->prepare("INSERT INTO Orders
								(Users_Id, OrderDate, Status)
                                VALUES (:idUser, NOW(), 'basket')");

            $query->execute(["idUser" => $idUser]) ;

            return $pdo->lastInsertId() ;

        }
    }

}
