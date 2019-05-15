<?php
class Router
{

    private $rootUrl ="/resto/index.php";
    private $wwwPath ="/resto/www";
    private $localhostPath= ABSOLUTE_ROOT_PATH;

    private $allRoutes =
    [
        "/"=>  [
            "controller"=>"Home",
            "method"=>"main",
            "name" =>"resto_home"
        ],
        //**************************************************inscription**********************
        "/user/inscription"=>[
            "controller"=>"User",
            "method"=>"signUp",
            "name" =>"resto_user_signup"
        ],
        //***************************************************connexion********************************

        "/user/connexion"=>[
            "controller"=>"User",
            "method"=>"login",
            "name" =>"resto_user_login"
        ],
        "/user/déconnexion"=>[
            "controller"=>"User",
            "method"=>"logout",
            "name" =>"resto_user_logout"
        ],
        //****************************************************reservation****************************

        "/reservation"=>[
            "controller"=>"Reservation",
            "method"=>"create",
            "name" =>"resto_reservation_create"
        ],
        "/reservation/annuler"=>[
            "controller"=>"Reservation",
            "method"=>"cancel",
            "name" =>"resto_reservation_cancel"
        ],
    //**********************************************contact********************************

        "/contact" =>[
            "controller" =>"Contact",
            "method"=>"contact",
            "name"=>"resto_user_contact"
        ],

        //**********************************************plats******************************

        "/plats" =>[
                    "controller" =>"Plat",
                    "method"=>"showAll",
                    "name"=>"resto_plat_showAll"
                   ],
        "/plat/ajouter" =>[
            "controller" =>"Plat",
            "method"=>"create",
            "name"=>"resto_plat_create"
        ],

        "/plat/remove"=>[
            "controller" =>"Plat",
            "method"=>"remove",
            "name"=>"resto_plat_remove"
        ],

        "/plat/remove-ajax"=>[
            "controller" =>"Plat",
            "method"=>"removeAjax",
            "name"=>"resto_plat_removeajax"
        ],

        "/plat/update"=>[
            "controller" =>"Plat",
            "method"=>"update",
            "name"=>"resto_plat_update"
        ],

        "/plat/details"=>[
            "controller" =>"Plat",
            "method"=>"show",
            "name"=>"resto_plat_show"
        ],



        //********************************************menu*******************************
        "/menus" =>[
            "controller" =>"Menu",
            "method"=>"showAll",
            "name"=>"resto_menu_showAll"
        ],
        "/menu/ajouter"=>[
            "controller" =>"Menu",
            "method"=>"create",
            "name"=>"resto_menu_create"
        ],
        "/menu/details"=>[
            "controller" =>"Menu",
            "method"=>"show",
            "name"=>"resto_menu_show"
        ],

        "/menu/modifier" =>[
            "controller" =>"Menu",
            "method"=>"update",
            "name"=>"resto_menu_update"
        ],
        "/menu/remove" =>[
            "controller" =>"Menu",
            "method"=>"remove",
            "name"=>"resto_menu_remove"
        ],
        "/menu/remove-ajax" =>[
        "controller" =>"Menu",
        "method"=>"removeAjax",
        "name"=>"resto_menu_removeajax"
        ],
        //******************************************Commande**************************************************************//
        "/mon-panier"
        => [
            "controller" => "Order", //Menus
            "method"     => "showAllByUser",
            "name"       => "resto_order_showAll",
        ],
        "/order/ajouter"
        => [
            "controller" => "Order", //Menus
            "method"     => "addOrderLine",
            "name"       => "resto_order_add",
        ],
        "/order/update-item-quantity"
        => [
            "controller" => "Order", //Menus
            "method"     => "updateItemQuantity",
            "name"       => "resto_order_updateItemQuantity",
        ],
        "/order/remove-item"
        => [
            "controller" => "Order", //Menus
            "method"     => "removeItem",
            "name"       => "resto_order_removeItem",
        ],
        "/order/empty-basket"
        => [
            "controller" => "Order", //Menus
            "method"     => "emptyBasket",
            "name"       => "resto_order_emptyBasket",
        ],

        //---------------------fin route

    ];
    private $allUrls = [];

    public function __construct()
    {
        foreach ($this->allRoutes as $url=>$route)
        {
            $this->allUrls[$route["name"]]=["url"=>$url,
                                            "route"=>[ "controller"=>$route["controller"],
                                                       "method"=>$route["method"]
                                                     ],
                                           ];
        }
        $nbrSlashes = substr_count($this->rootUrl,"/");
        $this->localhostPath = dirname($this->localhostPath,$nbrSlashes-1);
        //die($this->localhostPath);

    }
    public function getRoute($requestedPath)
    {
        if(isset($this->allRoutes[$requestedPath]))
        {
            return $this->allRoutes[$requestedPath];
        }
        else
        {
            die("ERROR:URL inconnu");
        }
    }
    public function getWwwPath($absolute=false)
    {
        if($absolute)
        {
            //pour windows il faut ajouter realpath
            return realpath($this->localhostPath.$this->wwwPath);
        }
        else
        {
            return $this->wwwPath;
        }
    }
    public function generatePath($routeName)
    {
        return $this->rootUrl.$this->allUrls[$routeName]["url"];
    }
}