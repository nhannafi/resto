<?php
class ReservationController
{
    private $maxSiege = 20;
    public function createAction()
    {
        $userSession = new UserSession();
        $flashbag = new Flashbag();
        if(!$userSession->isAuthenticated())
        {
            $flashbag->addMessage("Veuiller vous indentifier!");
            return['redirect'=>"resto_user_login"];
        }
        if($_POST)
        {
            $model= new ReservationModel();
            $Date_Res = $_POST["jour"]."-";
            $Date_Res .= $_POST["mois"]."-";
            $Date_Res .= $_POST["annee"];

            $nbrTaken = $model->findByDateAndService($Date_Res, $_POST['Service']);

            if (intval($_POST['Nbr_sieges']) + intval($nbrTaken) > $this->maxSiege)
            {
                $flashbag->addMessage("Plus assez de places disponible");
                return ["redirect" => "resto_reservation_create"];
            }

            $model->create($_POST['Nbr_sieges'],$userSession->getId(), $Date_Res,$_POST['Service']);
            $model=new Flashbag();
            $model->addMessage("Réservation a été engistrer avec succés!");
            $redirect="resto_resvation_create" ;

        }
        return[
            "template"=>[
                        "folder"=>"Reservation",
                        "file"=>"create"
                        ],
            "title"=>"Réservation"
        ];
    }
    public function detailAction()
    {

        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
//            $model = new ReservationModel();
//            $model->remove($_GET["id"]);
//            $flashbag= new flashbag();
//            $flashbag->addMessage("Votre reservation a été supprimé ");
        }

        return [ "redirect" => "resto_home"];

    }

    public function cancelAjaxAction()
    {
        if(isset($_GET["id"]) && ctype_digit($_GET["id"]))
        {
            $model = new ReservationModel();
            $resa=$model->find($_GET["id"]);

            $userSession = new UserSession();
            if ($resa &&  $resa["User_Id"] == $userSession->getId())
            {
                $model->cancel($_GET["id"]);
                return ["jsonResponse"=>true] ;
            }
            return ["jsonResponse"=>false] ;
        }
        return ["jsonResponse"=>false] ;
    }
}