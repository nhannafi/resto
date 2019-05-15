<?php
class UserModel
{
    public function create( $prenom,$nom, $email,$telephone, $password, $adress , $ville  ,$codepostal,$pays)
    {
        $pdo =(new Database())->getPdo();
        $query =$pdo->prepare('INSERT INTO users
                                        (`Prenom`, `Nom`, `Email`, `Password`, `Telephone`, `Adresse`, `Ville`, `Code_Postal`, `Pays`, `IsAdmin`, `Date_Creation`, `Derniere_Cnx`)
                                       VALUES ( :FirstName, :LastName, :Email,:Password, :Telephone, :Adresse,:Ville, :Code_Postal,:Pays, 0, NOW(), "2010-01-01 00:00:00" )');
        $query->execute([   "FirstName" => $prenom,
                            "LastName" => $nom,
                            "Email" => $email,
                            "Password" => password_hash($password,PASSWORD_BCRYPT),
                            "Telephone" => $telephone,
                            "Adresse" => $adress,
                            "Ville" => $ville,
                            "Code_Postal" => $codepostal,
                            "Pays" => $pays,
                          ]);
    }
    public function isEmailTaken($email)
    {
        $pdo=(new Database())->getPdo();
        $query= $pdo->prepare('SELECT COUNT(*) AS nbr
                                       FROM users
                                       WHERE Email = :email');
        $query->execute(["email" => $email ]) ;
        $result=$query->fetch();
        return $result['nbr'] > 0 ;
    }

    public function findByEmailandCheckPwd($email,$password)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare('SELECT * 
                                        FROM users
                                        WHERE Email = :email');
        $query->execute(["email"=>$email]);
        $user=$query->fetch();

        if(empty($user))
        {
            throw new DomainException("email inconnu");// envoyer une exception c'est pour le développeur c'est pas afficher a l'utilistauer
        }
        if(! password_verify($password ,$user["Password"]))
        {
            throw new DomainException("mot de passe incorrect");
        }

        return $user;
    }
    public function updateLoginDate($id)
    {
        $pdo = (new Database())->getPdo();
        $query = $pdo->prepare('UPDATE users 
                                        SET Derniere_Cnx = NOW()
                                        WHERE Id=:id');
        $query->execute(["id"=>$id]);
    }
}