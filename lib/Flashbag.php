<?php
class Flashbag
{
    public function __construct()
    {
        if (session_status()==PHP_SESSION_NONE)
        {
            session_start();
        }
        if(!array_key_exists("flashbag",$_SESSION))
        {
            $_SESSION["flashbag"]=[];
        }
    }
    public function addMessage($message)
    {
        $_SESSION["flashbag"][]=$message;//ajouter un message dans un tab
    }
    public function getAllMessages()
    {
        $allMessages=$_SESSION["flashbag"];
        $_SESSION["flashbag"]=[];
        return $allMessages;
    }
}