<?php

class PlatController
{
    private $allowedExtensions = [".jpg",".jpeg",".png"];
    public function showAction()
    {
        $redirect = null ;
        $plat = null ;
        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new PlatModel();
            $plat = $model->find($_GET["id"]) ;
            if(!$plat)
            {
                $redirect = "resto_plat_showAll" ;
            }
        }
        else
        {
            $redirect = "resto_plat_showAll" ;
        }
        return ["template" => ["folder" => "Plat",
                                 "file"   => "show",],
                "plat"      => $plat,
                "redirect"  => $redirect,
                "title"     =>  "le plat en détail",
                ];
    }

    public function showAllAction()
    {
        $model= new PlatModel();
        return [
            "template"=>
             [
                "folder"=>"Plat",
                "file"=>"showAll",
             ],
            "allPlats"=>$model->findAll(),
            "title"=>"Tous nos Plats",
        ];
    }

    public function createAction()
    {

        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return["redirect" => "resto_home"];
        }

        $redirect = null ;
        $flashbag = new Flashbag();
        $flashbag->addMessage("Plat créé avec succés");

        if(isset($_POST['titre']))
        {
            $allowedExtensions = [".jpg",".jpeg",".png"];
            $model = new PlatModel();
            $idPlat = $model->create($_POST['titre'], $_POST['description'],NULL , $_POST['Prix_Achat'], $_POST['Prix_Vente'] );
            $originalName = $_FILES["image"]["name"];
            if(isset($_FILES["image"]) && $_FILES["image"]["error"]>=0)
            {
                $extension = substr($originalName, strpos($originalName,".",strlen($originalName)-5 ));

                if(in_array($extension,$allowedExtensions))
                {
                    $filename="plat_".$idPlat.$extension;
                    $router = new Router();
                    $filePath = $router->getWwwPath(true)."/upload/plats/";

                    if (move_uploaded_file($_FILES["image"]["tmp_name"],$filePath.$filename))
                    {
                        $model->updateImg($idPlat, $filename) ;
                    }
                }
            }
            $redirect="resto_plat_showAll" ;
        }
        return[
                "template"=>[
                                "folder"=>"Plat",
                                "file"=>"create"
                            ],
                "redirect"=> $redirect,
                "title"=>"+ de plats"
              ];
    }
    public function updateAction()
    {
        $userSession = new UserSession();
        if(! $userSession->isAdmin())
        {
            return ["redirect" => "resto_home"];
        }

        $plat = null;

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new PlatModel();
            $plat = $model->find($_GET['id']) ;

            if(!$plat)
            {
                $flashbag = new Flashbag();
                $flashbag->addMessage("plat introuvable");
                return ["redirect" =>"resto_plat_showAll"] ;
            }
        }
        elseif(isset($_POST["titre"]))
        {

            $model = new PlatModel();
            $model->update($_POST["idplat"],$_POST['titre'], $_POST['description'], $_POST['Prix_Achat'], $_POST['Prix_Vente'] );

            $router = new Router();
            $plat = $model->find($_POST['idplat']) ;
            $filepath = $router->getWwwPath(true).'/upload/plats/' ;

            if(isset($_FILES["image"]) && $_FILES["image"]['size'] > 0 && $_FILES["image"]["error"] >=0)
            {

                //ajouter la nouvelle image
                $originalName = $_FILES["image"]['name'] ;
                $extension = substr($originalName, strpos($originalName,".",strlen($originalName)-5)) ;
                if(in_array($extension, $this->allowedExtensions ))
                {
                    $filename = 'plat-'.$_POST['idplat'].$extension ;

                    if(move_uploaded_file($_FILES['image']['tmp_name'], $filepath.$filename))
                    {
                        if($plat['Images'])
                        {
                            //effacer l'image existance s'il y en a une
                            $oldFilename = $plat['Images'] ;
                            $fullFilePath = $filepath.$oldFilename ;
                            if($oldFilename != $filename && file_exists($fullFilePath))
                            {
                                unlink($fullFilePath);
                            }
                        }

                        $model->updateImg($_POST['idplat'], $filename) ;
                    }
                }
            }
            elseif(isset($_POST['photo-suppr']) && $_POST['photo-suppr'])
            {
                //Pas d'image choisie, mais checkbox coché "supprimer l'image
                if($plat['Images'])
                {
                    //effacer l'image existance s'il y en a une
                    $filename = $plat['Images'] ;
                    $fullFilePath = $filepath.$filename ;
                    if(file_exists($fullFilePath))
                    {
                        unlink($fullFilePath);
                    }
                }

                $model->updateImg($_POST['idplat'], NULL) ;
            }

            $flashbag = new Flashbag();
            $flashbag->addMessage("plat mis à jour avec succès");
            return ["redirect" =>"resto_plat_showAll"] ;
        }
        else
        {
            return ["redirect" =>"resto_plat_showAll"] ;
        }

        return[
            "template"=>[
                "folder"=>"Plat",
                "file"=>"update"
            ],
            'plat'=>$plat
        ];
    }
    public function removeAction()
    {
        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return["redirect" => "resto_home"];
        }

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new PlatModel();
            $plat = $model->find($_GET["id"]);
            if($plat)
            {
                if($plat['Images'])
                {
                    //effacer l'image existance s'il y en a une
                    $oldFilename = $plat['Images'] ;
                    $router = new Router();
                    $filepath = $router->getWwwPath(true).'/upload/plats/' ;
                    $fullFilePath = $filepath.$oldFilename ;
                    if(file_exists($fullFilePath))
                    {
                        unlink($fullFilePath);
                    }
                }
                $model->remove($_GET["id"]);
            }
        }
        return[ "redirect"=>"resto_plat_showAll" ];
    }

    public function removeAjaxAction()
    {
        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return["jsonResponse" => json_encode(false)];
        }

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new PlatModel();
            $plat = $model->find($_GET["id"]);
            if($plat)
            {
                if($plat['Images'])
                {
                    //effacer l'image existance s'il y en a une
                    $oldFilename = $plat['Images'] ;
                    $router = new Router();
                    $filepath = $router->getWwwPath(true).'/upload/plats/' ;
                    $fullFilePath = $filepath.$oldFilename ;
                    if(file_exists($fullFilePath))
                    {
                        unlink($fullFilePath);
                    }
                }

                $model->remove($_GET["id"]);
            }
        }
        return[ "jsonResponse" => json_encode(true) ];

    }




}