<?php
class UserController
{
    public function signUpAction()
    {
        if($_POST)
        {
            $model = new UserModel();
            $error = false ;
            $flashbag = new Flashbag();
            //Vérifications des champs du formulaire
            if(! isset($_POST['prenom']) || empty(trim($_POST['prenom'])))
            {
                $flashbag->addMessage("Veuillez entrez un prenom valide");
                $error = true ;
            }

            if(! isset($_POST['nom']) || empty(trim($_POST['nom'])))
            {
                $flashbag->addMessage("Veuillez entrez un nom valide");
                $error = true ;
            }

            if(! isset($_POST['email']) || empty(trim($_POST['email'])))
            {
                $flashbag->addMessage("veuillez entrez un email ");
                $error = true ;
            }
            elseif ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $flashbag->addMessage("veuillez entrez un email valide");
                $error = true ;
            }
            elseif($model->isEmailTaken($_POST['email']))
            {
                $flashbag->addMessage("email déjà pris");
                $error = true ;
            }


            if(! isset($_POST['password']) || empty(trim($_POST['password'])))
            {
                $flashbag->addMessage("Veuillez entrez un mot de passe ");
                $error = true ;
            }
            elseif(strlen($_POST['password'])<6)
            {
                $flashbag->addMessage("Mot de passe faible! Veuillez entrez un plus long que caractère ");
                $error = true ;
            }
            elseif(!preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#',$_POST['password']))
            {
                $flashbag->addMessage("le mot passe doit obligatoirement contenir au moins une lettre en majuscule,miniscule,un caractère spéciale");
                $error = true ;
            }
            if(! isset($_POST['tel']) || empty(trim($_POST['tel'])))
            {
                $flashbag->addMessage("Veuillez entrez un numéro de télephone ");
                $error = true ;
            }
            if(! isset($_POST['address']) || empty(trim($_POST['address'])))
            {
                $flashbag->addMessage("Veuillez entrez une adresse ");
                $error = true ;
            }
            if(! isset($_POST['ville']) || empty(trim($_POST['ville'])))
            {
                $flashbag->addMessage("Veuillez entrez une ville");
                $error = true ;
            }
            if(! isset($_POST['postalCode']) || empty(trim($_POST['postalCode'])))
            {
                $flashbag->addMessage("Veuillez entrez un code postal ");
                $error = true ;
            }


            if($error)
            {
                return ["redirect" => "resto_user_signup"] ;
            }

            //Créer un nouvel utilisateur
//            create( $prenom,$nom, $email,$telephone, $password, $adress , $ville  ,$codepostal,$pays)
            $model->create( strtolower($_POST['prenom']),
                            strtolower($_POST['nom']),
                            $_POST['email'],
                            $_POST['tel'],
                            $_POST["password"],
                            $_POST['address'],
                            $_POST['ville'],
                            $_POST['postalCode'],
                            $_POST['pays']);
            $flashbag = new Flashbag();
            $flashbag->addMessage("Compte créé avec succès :) Bienvenue".$_POST["prenom"]);
            return ["redirect" =>"resto_home"];
        }

        return[
            "template"=>[
                "folder"=>"User",
                "file"=>"signUp",
            ],
            "title" =>"Inscription",
        ];
    }
    public function loginAction()
    {
        if(isset($_POST["email"]) && isset($_POST["password"]))
        {
            $user=null;
            $model= new UserModel();
            try
            {
                $user=$model->findByEmailandCheckPwd($_POST["email"],$_POST["password"]);
            }
            catch (DomainException  $exception)
            {
                $flashbag = new Flashbag();
                //$flashbag->addMessage($exception->getMessage());
               $flashbag->addMessage("Email ou mot passe incorrect");
                return["redirect"=>"resto_user_login"];
            }

            //die (var_dump());
            $model->updateLoginDate($user["Id"]);
            $userSession = new UserSession();
            $userSession-> create($user['Id'],
                                  $user['Prenom'],
                                  $user['Nom'],
                                  $user['Email'],
                                  $user['IsAdmin']

                );
            return ["redirect"=>"resto_home"];
        }
        return[
                "template"=>[
                            "folder"=>"User",
                            "file"=>"login",
                            ],
                "title" =>"Connexion",
        ];
    }
    public function logoutAction()
    {
        $userSession = new UserSession();
        $userSession-> destroy();

        return["redirect"=>"resto_home"];

    }
    public function accountAction()
    {
        $userSession = new UserSession();

        if (!$userSession->isAuthenticated())
        {
            return ["redirect" => "resto_user_login"];
        }
        $reservationModel = new ReservationModel();
        $allReservations = $reservationModel->findByUser($userSession->getId());

        return [
            "template"
            => [
                "folder" => "User",
                "file" => "account",
            ],
            "allReservations"=>$allReservations,
        ];

    }
}