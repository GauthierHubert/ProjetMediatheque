	<?php
	$q=$_POST["id"];

	function connexion( ){
		$dbh = new PDO(
			"mysql:dbname=mediatheque;host=localhost;port=3308",
			"root",
			"",
			array(
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);
		return $dbh;
	}

	function affiche($dbh, $q){
		$tab=[];

		$sql = "select films_affiche,films_titre,films_annee, group_concat(distinct genres_nom) as genre,real_nom, group_concat(distinct acteurs_nom) as acteur,films_duree,films_resume from films 
		join films_acteurs on films_id=fa_films_id 
		join acteurs on fa_acteurs_id=acteurs_id
		join realisateurs on films_real_id=real_id
		left outer join films_genres on films_id=fg_films_id
		left outer join genres on fg_genres_id=genres_id
		where films_id=:id
		group by films_affiche,films_titre,films_annee,real_nom,films_duree,films_resume
		";

		$stmt = $dbh -> prepare($sql);

		$stmt -> bindValue('id', $q, PDO::PARAM_INT);
		//faire binParam() ou bindValue() si paramètres
		//3. Exécution de la requête

			$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_ASSOC); //donc le tableau associatif résultant prendra écomme clef les noms des colonnes

		$tab=$stmt->fetchAll(); //pour remplir le tableau du set résultat

		return $tab;
	}
	 
	try{
		$dbh=connexion();
		$tabaffiche=affiche($dbh,$q); 
		
	}catch (Exception $ex) {

		die("ERREUR FATALE : ". $ex->getMessage().'<form><input type="button" value="Retour"
		onclick="history.go(-1)"></form>');
		//die affiche un message puis stoppe l’exécution du script puisqu’on a un grave pb de DB
	}
	
	echo $tabaffiche[0]['films_affiche']."@".$tabaffiche[0]['films_titre']."@".$tabaffiche[0]['films_annee']."@".$tabaffiche[0]['genre']."@".$tabaffiche[0]['real_nom']."@".$tabaffiche[0]['acteur']."@".$tabaffiche[0]['films_duree']."@".$tabaffiche[0]['films_resume'];
	
	?>
