<?php
class ReservationModel
{
    public function create($nbrSieges,$userId,$dateReser,$service)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("INSERT INTO reservation
                                        (Nbr_Sieges,User_Id,Date_Reserv,Service)
                                         VALUES(:nbrSieges,:userId,:dateReser,:service)
                                       WHERE Id = :id");

        $query->execute( ["nbrSieges" => $nbrSieges,
                         "userId" => $userId,
                         "dateReser" => $dateReser,
                         "service" => $service,
                         ]);

    }
    public function findByDateAndService($date_Res,$service)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare("SELECT SUM(Nbr_Sieges) AS nbr
                                        FROM reservation
                                        WHERE Date_Reserv = :date_Res
                                        AND Service = :service
                                        AND Canceled = 0
                                ");
        $query->execute([
                          "date_Res"=>$date_Res,
                          "service"=>$service,
                        ]);
        $result=$query->fetch();
        return $result['nbr'];
    }

    public function findByUserId()
    {
//        $pdo = (new Database())->getPdo();
//        $query = $pdo->prepare()

    }
    public function cancel()
    {

    }
}