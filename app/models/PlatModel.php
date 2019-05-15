<?php
class PlatModel
{
    public function findAll()
    {
        $pdo = (new Database())->getPdo();
        $result=$pdo->query("SELECT *
                              FROM plats
                              ORDER BY Titre
                    
                    ");
        return $result->fetchAll();
    }
    public function find($id)
    {
        $pdo = (new Database())->getPdo();
        $query=$pdo->prepare("SELECT *
                               FROM plats
                               WHERE Id= :id
                    
                             ");
        $query->execute(["id" => $id]);
        return $query->fetch();
    }
    public function findAllOrderByMenu()
    {
        $pdo = (new Database())->getPdo();
        $result= $pdo->query("SELECT Menu_Id, Plat_Id,Titre
                                     FROM plats
                                     INNER JOIN plats_menus
                                     ON plats.Id = plats_menus.Plat_Id
                                     ORDER BY Menu_Id"
                            );
        return $result->fetchAll();
    }
    public function findAllIdsAndTitles()
    {
        $pdo = (new Database())->getPdo();
        $result = $pdo->query(
            "SELECT Id,Titre
                     FROM Plats
                     ORDER  BY Titre  ");
        return $result->fetchAll();
    }

    public function findByMenu($idMenu)
    {
        $pdo = (new Database())->getPdo() ;
        $query = $pdo->prepare("SELECT Id, Titre, Images
                                        FROM plats 
                                        INNER JOIN plats_menus
                                        ON Id = Plat_Id
                                        WHERE Menu_Id = :idMenu");

        $query->execute(['idMenu' => $idMenu]) ;

        return $query->fetchAll();
    }

    public function findIdsByMenu($idMenu)
    {
        $pdo = (new Database())->getPdo() ;
        $query = $pdo->prepare("SELECT Plat_Id 
                                        FROM plats_menus
                                        WHERE Menu_Id = :idMenu");

        $query->execute(['idMenu' => $idMenu]) ;

        return $query->fetchAll(PDO::FETCH_COLUMN);
    }


    public function findAllIdsNamesAndCheckedByMenu($idMenu)
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("SELECT Titre,Id, (Menu_Id IS NOT NULL) AS checked
                                        FROM plats
                                        LEFT JOIN plats_menus
                                        ON plats.Id=plats_menus.Plat_Id
                                        AND Menu_Id = :idMenu
                                        "
        );

        $query->execute(["idMenu"=>$idMenu]);
        return $query->fetchAll();
    }
    public function create($title , $description , $images ,$buyPrice , $sellPrice )
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("INSERT INTO plats
                                      (Titre, Description, Images, Prix_Reviens, Prix_Vente)
                                       VALUES (:Titre, :Description, :Images, :Prix_Reviens, :Prix_Vente)");

        $query->execute(["Titre"    => $title,
                        "Description"  => $description,
                        "Prix_Reviens"  => $buyPrice,
                        "Prix_Vente"  => $sellPrice,
                        "Images"  => $images
                        ]);
        return $pdo->lastInsertId();
    }
    public function remove($id)
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("DELETE FROM plats
                                        WHERE Id =:Id
                              ");
        $query->execute(["Id" => $id]);
    }
    public function updateImg($id,$filename)
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("UPDATE plats
                                        SET Images =:filename
                                        WHERE Id =:Id
                              ");
        $query->execute(["Id" => $id,
            "filename" => $filename
        ]);
    }

    public function update($id, $title , $description ,$buyPrice , $sellPrice )
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare("UPDATE plats 
                                        SET Titre =:Titre, 
                                        Description=:Description, 
                                        Prix_Reviens=:Prix_Reviens, 
                                        Prix_Vente=:Prix_Vente
                                        WHERE Id =:Id
                              ");
        $query->execute(["Titre"    => $title,
                         "Description"  => $description,
                        "Prix_Reviens"  => $buyPrice,
                        "Prix_Vente"  => $sellPrice,
                        "Id"=> $id,

                        ]);
    }
}