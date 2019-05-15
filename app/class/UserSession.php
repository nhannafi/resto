<?php
class UserSession
{
    public function __construct()
    {
        if(session_status()==PHP_SESSION_NONE)
        {
            session_start();
        }
    }
    public function create($id,$firstName,$lastName,$email,$admin)
    {
        $_SESSION["users"] = ["Id"=>$id,
                             "Prenom" =>$firstName,
                             "Nom" => $lastName,
                             "Email" =>$email,
                             "IsAdmin" =>$admin,
                            ];
    }
    public function destroy()
    {
        $_SESSION = [];
        session_destroy();
    }
    public function isAuthenticated()
    {
        return(isset($_SESSION["users"]));

    }
    public function isAdmin()
    {
        if(!$this->isAuthenticated())
        {
            return false;
        }
        return $_SESSION["users"]["IsAdmin"];
    }
    public function getId()
    {
        if(! $this->isAuthenticated())
        {
            return null;
        }
        return $_SESSION["users"]["Id"];
    }
    public function getFirstName()
    {
        if(! $this->isAuthenticated())
        {
            return null;
        }
        return $_SESSION["users"]["Prenom"];
    }
    public function getLastName()
    {
        if(! $this->isAuthenticated())
        {
            return null;
        }
        return $_SESSION["users"]["Nom"];
    }
    public function getEmail()
    {
        if(! $this->isAuthenticated())
        {
            return null;
        }
        return $_SESSION["users"]["Email"];
    }

}