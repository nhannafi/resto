<?php

class HomeController
{
    public function mainAction()
    {
        return[
            "template"=> [
                "folder"=>"Home",
                "file"=>"main",
            ],
            "title"=> "Accueil",

        ];
    }

}