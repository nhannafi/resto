<?php

class OrderController
{

    public function showAllByUserAction()
    {

        $userSession = new UserSession();
        if(! $userSession->isAuthenticated())
        {
            return ["redirect" => "resto_user_login"] ;
        }

        $model = new OrderModel();
        $idOrder = $model->getUserBasketId($userSession->getId()) ;

        $orderDetailModel = new OrderDetailModel();
        $allOrderLines = $orderDetailModel->findBasketByOrder($idOrder) ;

        return [
            "template" =>
                [
                    "folder" => "Order",
                    "file" => "showAll",
                ],
            "title" => "Mon Panier",
            "allOrderLines" => $allOrderLines,
            "idOrder" => $idOrder,
        ];
    }


    public function addOrderLineAction()
    {

        $userSession = new UserSession();
        $router = new Router() ;
        if(! $userSession->isAuthenticated())
        {
            return ["jsonResponse" =>
                json_encode(
                    [
                        "success" => false ,
                        "redirect" => $router->generatePath("resto_user_login") ,
                    ])
            ] ;
        }

        if(isset($_GET['id']) && ctype_digit($_GET['id']) && isset($_GET['q']) && ctype_digit($_GET['q']) )
        {

            $menuModel = new MenuModel() ;
            $menu = $menuModel->find($_GET['id']) ;
            if($menu)
            {
                $idUser = $userSession->getId() ;
                $orderModel = new OrderModel();
                $idOrder = $orderModel->getUserBasketId($idUser) ;

                $orderDetailModel = new OrderDetailModel();
                $orderDetailModel->addOrCreateToMenu($idOrder, $_GET['id'], $_GET['q'], $menu['Price']) ;

                return ["jsonResponse"
                => json_encode(
                        [
                            "success" => true,
                            "message" => "bien ajouté au panier"
                        ])
                ] ;

            }
        }

        return ["jsonResponse" =>
            json_encode(
                [
                    "success" => false ,
                    "message" => "ça marche pas, apeler Tom",
                ])
        ] ;

    }

    public function emptyBasketAction()
    {
        $userSession = new UserSession();
        if(! $userSession->isAuthenticated())
        {
            return ["redirect" => "resto_user_login"] ;
        }

        $model = new OrderModel();
        $idOrder = $model->getUserBasketId($userSession->getId()) ;

        $orderDetailModel = new OrderDetailModel();
        $orderDetailModel->removeByOrder($idOrder) ;

        return [
            "template" =>
                [
                    "folder" => "Order",
                    "file" => "showAll",
                ],
            "title" => "Mon Panier",
            "allOrderLines" => [],
        ];
    }

    public function updateItemQuantityAction()
    {
        $userSession = new UserSession();
        $router = new Router() ;
        if(! $userSession->isAuthenticated())
        {
            return ["jsonResponse" =>
                json_encode(
                    [
                        "success" => false ,
                        "redirect" => $router->generatePath("resto_user_login") ,
                    ])
            ] ;
        }

        if(isset($_GET['id']) && ctype_digit($_GET['id']) && isset($_GET['q']) && ctype_digit($_GET['q']) )
        {

            $orderDetailModel = new OrderDetailModel();
            $orderLine = $orderDetailModel->find($_GET['id']);

            if ($orderLine)
            {
                $orderModel = new OrderModel();
                $idOrder = $orderModel->getUserBasketId($userSession->getId());
                if($idOrder != $orderLine["Orders_Id"] )
                {
                    return ['jsonResponse' => json_encode(

                        [
                            "success" => false ,
                        ])

                    ] ;
                }


                if($_GET['q'] == 0)
                {
                    $orderDetailModel->remove($_GET['id']);
                    return ["jsonResponse"
                    => json_encode(
                            [
                                "success" => true,
                                "todo"    =>"delete",
                            ])
                    ];
                }
                else
                {
                    $orderDetailModel->updateQuantity($_GET['id'], $_GET['q']);
                    return ["jsonResponse"
                    => json_encode(
                            [
                                "success" => true,
                                "todo"   => "updateQuantity",
                                "quantity" => $_GET['q'],
                            ])
                    ];
                }
            }

        }
        return ["jsonResponse"
        => json_encode(
                [
                    "success" => false,
                    "message" => "erreur pas vraiment prévue dans le code"
                ])
        ];
    }


    public function removeItemAction()
    {
        $userSession = new UserSession();
        $router = new Router() ;
        if(! $userSession->isAuthenticated())
        {
            return ["jsonResponse" =>
                json_encode(
                    [
                        "success" => false ,
                        "redirect" => $router->generatePath("resto_user_login") ,
                    ])
            ] ;
        }

        if(isset($_GET['id']) && ctype_digit($_GET['id']) )
        {

            $orderDetailModel = new OrderDetailModel();
            $orderLine = $orderDetailModel->find($_GET['id']);

            if ($orderLine)
            {
                $orderModel = new OrderModel();
                $idOrder = $orderModel->getUserBasketId($userSession->getId());
                if($idOrder != $orderLine["Orders_Id"] )
                {
                    return ['jsonResponse' => json_encode(

                        [
                            "success" => false ,
                        ])

                    ] ;
                }

                $orderDetailModel->remove($_GET['id']);
                return ["jsonResponse"
                => json_encode(
                        [
                            "success" => true,
                            "todo"   => "delete",
                        ])
                ];
            }

        }
        return ["jsonResponse"
        => json_encode(
                [
                    "success" => false,
                    "message" => "problème dans le code = ????"
                ])
        ];


    }

}