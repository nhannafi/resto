<?php
class
ContactController
{
    public function contactAction()
    {
        return [
            "template" => [
                "folder" => "Contact",
                "file" => "contact",
            ],
            "title" => "Contactez-nous !",
        ];
    }
}
