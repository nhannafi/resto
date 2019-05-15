<?php
class MenuModel
{
    public function find($id)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("SELECT *
                                       FROM menus
                                       WHERE Id = :id");
        $query->execute(["id" => $id]);
        return $query->fetch();
    }
    public function findAll()
    {
        $pdo = (new Database())->getPdo();
        $result=$pdo->query("SELECT *
                                      FROM menus
                                      ORDER BY Titre
                                    ");
        return $result->fetchAll();
    }

    public function create ($titre,$dateDebut,$dateFin)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("INSERT INTO menus
                                          (Titre, Date_Debut, Date_Fin )
                                           VALUES (:Titre, :DateDebut, :DateFin )");
        $query->execute(["Titre" => $titre,
                         "DateDebut" => $dateDebut,
                         "DateFin" => $dateFin,
                       ]);
        return $pdo->lastInsertId();
    }

    public function addPlats($idMenu, $idPlatsArray)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("INSERT INTO menus_plats
                                          (Menu_Id , Plat_Id)
                                           VALUES (:idMenu , :idPlat)");
        foreach ($idPlatsArray as $idPlat)
        {
            $query->execute(
            [
                "idMenu"=>$idMenu,
                "idPlat"=>$idPlat,
            ]);
        }
    }
    public function removePlats($idMenu, $idPlatsArray)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("DELETE FROM menus_plats
                                        WHERE Menu_Id = :idMenu AND Plat_Id = :idPlat
                                        ");
        foreach ($idPlatsArray as $idPlat)
        {
            $query->execute([
                                "idMenu" => $idMenu,
                                "idPlat" => $idPlat,
                            ]);
        }
    }
    public function update($id,$title, $Date_Debut, $Date_Fin)
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("UPDATE menus 
                                        SET Titre =:Titre,
                                        Date_Debut=:Date_Debut, 
                                        Date_Fin=:Date_Fin,
                                        WHERE Id =:Id
                              ");
        $query->execute(["Titre"    => $title,
                        "Date_Debut"  => $Date_Debut,
                        "Date_Fin"  => $Date_Fin,
                        "Id"=> $id
                        ]);
    }

    public function remove($id)
    {
        $pdo=(new Database())->getPdo();
        $query = $pdo->prepare("DELETE FROM menus
                                            WHERE Id = :Id ");
        $query->execute(["Id"=>$id]);
    }
}
