<?php
class MenuController
{
    public function showAction()
    {
        $redirect = null ;

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new MenuModel();
            $menu = $model->find($_GET["id"]) ;
            if(!$menu)
            {
                return ['redirect' => "resto_plat_showAll"] ;
            }

            $platModel = new PlatModel();
            $plats = $platModel->findByMenu($_GET["id"]);
        }
        else
        {
            return ['redirect' => "resto_plat_showAll"] ;
        }

        return ["template" => ["folder" => "Menu",
                                        "file"   => "show",],
                        "menu"      => $menu,
                        "plats"      => $plats,
                        "title"     =>  "le menu en détail",
                        ];
    }


    public  function showAllAction()
    {
        $menuModel=new MenuModel();
        $platModel=new PlatModel();
        $allMenus=$menuModel->findAll();
        $allPlatsRaw=$platModel->findAllOrderByMenu();
        $allPlats=[];
        foreach ($allPlatsRaw as $plat)
        {
            $allPlats[$plat["Menu_Id"]][]=
                [
                    "Id"=>$plat["Plat_Id"],
                    "Titre"=>$plat["Titre"]
                ];
        }
        unset($allPlatsRaw);

        return["template" =>["folder" =>"Menu",
                            "file"=>"showAll"],
                'allMenus' => $allMenus,
                "allPlats"=> $allPlats
              ];
    }
    public function createAction()
    {
        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return ['redirect' => "resto_home"];
        }

        $redirect = null;
        $platModel = new PlatModel();
        $allPlats = $platModel->findAllIdsAndTitles();
        if(isset($_POST["titre"]))
        {
            if(! isset($_POST["plats"]) ||  count($_POST["plats"]) < 2)
            {
                $flashbag = new Flashbag();
                $flashbag->addMessage("veuillez choisir au moins deux plats");
                return ['redirect' => "resto_menu_create"];
            }
            $dateDebut = $_POST['anneeDebut']."-";
            $dateDebut .= $_POST['moisDebut']."-";
            $dateDebut .= $_POST['jourDebut'];

            $dateFin = $_POST['anneeFin']."-";
            $dateFin .= $_POST['moisFin']."-";
            $dateFin .=$_POST['jourFin'];

            $model = new MenuModel();
            $idMenu = $model->create($_POST['titre'],$dateDebut,$dateFin);
            $model->addPlats($idMenu,$_POST["plats"]);
            $redirect = "resto_menu_showAll" ;
        }
        return[

            "template" =>["folder" =>"Menu",
                          "file"=>"create"],
            'redirect' => $redirect,
            "allPlats"=> $allPlats
        ];
    }
    public function updateAction()
    {
        $redirect = null ;
        $menu = null ;
        $allPlats = [];
        $model = new MenuModel();
        $platModel =new PlatModel();
        $userSession = new UserSession();
        $plat = null;

        if(! $userSession->isAdmin())
        {
            return ["redirect" => "resto_home"];
        }

        if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
            $model = new MenuModel();
            $menu = $model->find($_GET['id']);
            if (!$menu)
            {
                $flashbag = new Flashbag();
                $flashbag->addMessage("menu introuvable");
                return ["redirect" => "resto_menu_showAll"];
            }
            $platModel = new PlatModel();
            $allPlats = $platModel->findAllIdsNamesAndCheckedByMenu($_GET['id']);
        }
        elseif (isset($_POST['titre']))//pour afficher le Menu dans le formulaire
        {
            $oldPlats = $platModel->findIdsByMenu($_POST['id']) ;

            $addPlats = array_diff($_POST['idPlats'], $oldPlats);
            $removePlats = array_diff($oldPlats, $_POST['idPlats']);

            $model->update($_POST['Id'], $_POST['titre'], $_POST['Date_Debut'], $_POST['Date_Fin']  );

            $model->addPlats($_POST['id'], $addPlats);
            $model->removePlats($_POST['id'], $removePlats);

            return [ "redirect" => "resto_menu_showall" ] ;
        }
        return  ["template" => [
                                "folder" => "Menu",
                                "file" => "update"
                               ],
                "title" => "modifier un Menu",
                "redirect" => $redirect,
                "menu" => $menu,
                "allPlats" => $allPlats,
                ];
    }
    public function removeAction()
    {
        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return["redirect" => "resto_home"];
        }

        if(isset($_GET["id"])&& ctype_digit($_GET["id"]))
        {
            $model = new MenuModel();
            $model->remove($_GET["id"]);
        }
        return["redirect"=>"resto_menu_showAll"];
    }
    public function removeAjaxAction()
    {
        $userSession = new UserSession();
        if(!$userSession->isAdmin())
        {
            return["redirect" => "resto_home"];
        }

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new MenuModel();
            $model->remove($_GET["id"]);
        }

        return [ "jsonResponse" => json_encode(true) ];
    }

}
