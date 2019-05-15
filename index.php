<?php
define('ABSOLUTE_ROOT_PATH',__DIR__);
//die(ABSOLUTE_ROOT_PATH);
include"lib/Router.php";
include "lib/Kernel.php";
include "lib/Database.php";
include "lib/Flashbag.php";

$kernel = new Kernel();
$kernel->bootstrap();
//$kernel->run();
//$kernel->renderResponse();
//ou bien on ecrit ça :
try
{
    ob_start();
    $kernel->run();
    $kernel->renderResponse();
    ob_end_flush();
}
catch(Exception $exception)
{
    $kernel->renderError(implode("<br>",
                                    [$exception->getMessage(),
                                    "<strong>Fichier </strong>".$exception->getFile(),
                                    "<strong>Line: </strong>".$exception->getLine()
                                    ]
                        ));
    ob_clean();
}