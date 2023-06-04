<?php

$head='';
$main='';

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

$dbh=connexion();

function liste($dbh){
    $tab=[];

    if(isset($_POST['recherche'])){
        $sql="select films_affiche,films_titre,films_annee, group_concat(distinct genres_nom),real_nom, group_concat(distinct acteurs_nom),films_duree,films_resume from films 
        join films_acteurs on films_id=fa_films_id 
        join acteurs on fa_acteurs_id=acteurs_id
        join realisateurs on films_real_id=real_id
        left outer join films_genres on films_id=fg_films_id
        left outer join genres on fg_genres_id=genres_id
        where 
        films_titre like '%".$_POST['recherche']."%' or 
        films_annee like '%".$_POST['recherche']."%' or 
        genres_nom like '%".$_POST['recherche']."%' or 
        acteurs_nom like '%".$_POST['recherche']."%' or
        real_nom like '%".$_POST['recherche']."%'
        group by films_id
        order by films_id
        ";

    }else{
       $sql = "select films_affiche,films_titre,films_annee, group_concat(distinct genres_nom),real_nom, group_concat(distinct acteurs_nom),films_duree,films_resume,count(*) from films 
       join films_acteurs on films_id=fa_films_id 
       join acteurs on fa_acteurs_id=acteurs_id
       join realisateurs on films_real_id=real_id
       left outer join films_genres on films_id=fg_films_id
       left outer join genres on fg_genres_id=genres_id
       group by films_id
       order by films_id
       ";}

    $stmt = $dbh -> prepare($sql);

    //faire binParam() ou bindValue() si paramètres
    //3. Exécution de la requête

    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC); //donc le tableau associatif résultant prendra écomme clef les noms des colonnes

    $tab=$stmt->fetchAll(); //pour remplir le tableau du set résultat

    return $tab;
}

$tab=liste($dbh);

function Head(){
    $head= "";

    $head.="<div class='head'>";
    $head.="<h1 class='font'id='animate'>Médiathèque</h1>";
    $head.="<div class='head-spe' id='node-id'></div>";
    $head.="<form action='LABOMain.php' class='form-group' method='POST' name='searchform'><input type='text' name='recherche' class='form-control empty inputsearch' value='' id='iconified' placeholder='        &#xF002;     Recherche'></form>";


    $head.="</div>";


    return $head;
}


$head=Head();

function Main($tab){

    $main='';
    $cpt='';

    $main.="<ul id='paginated-list' aria-live='polite'>";
    $cpt2=1;
    foreach($tab as $row){

        if($cpt==5){
            $cpt=0;
        }
        $image=$row['films_affiche'];
        $titre=$row['films_titre'];
        $real=$row['real_nom'];


        if($cpt==0){
        $main.="<li class='affiche first'>";
        $main.="<img class='img-affiche'  src='Image/$image'>";
        $main.="<p class='titre-affiche font' id='aff'>$titre</p>";
        $main.="<input type='button' value='' id='$cpt2' onClick='loadFilm(this.id)' name='test'>";
        $main.="<p class='font spe-p'>Voir Plus</p>";

        $main.="</li>";
        } 
        else{
        $main.="<li class='affiche'>";
        $main.="<img class='img-affiche' src='Image/$image'>";
        $main.="<p class='titre-affiche font' id='aff'>$titre</p>";
        $main.="<input type='button' value='' id='$cpt2' onClick='loadFilm(this.id)' name='test'>";
        $main.="<p class='font spe-p'>Voir Plus</p>";
        $main.="</li>";
        }

        $cpt++;
        $cpt2++;
    }
    $main.="</ul>";

 


    return $main;

}

function Footer(){

    $foot='';

    $foot.="<nav class='pagination-container'>";

    $foot.="<button class='pagination-button' id='prev-button' title='Previous page' aria-label='Previous page'> &lt; </button>";

    $foot.="<div id='pagination-numbers'></div>";
  
    $foot.="<button class='pagination-button' id='next-button' title='Next page' aria-label='Next page'> &gt; </button>";
  
    $foot.="</nav>";
 
    return $foot;
}

$foot=Footer();

$main=Main($tab);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="Styles/normalize.css" >
    <link rel="stylesheet" href="Styles/main3.css" >


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/13c73900d8.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">

    <script src="jquery-3.6.2.min.js"></script>
    <script scr="js.js"></script>
    <script src="js_lent.js" defer></script>



    <title>Médiathèque</title>
    
</head>
<body>
    <?php echo $head ?>
    
    <?php echo $main ?>

    <?php echo $foot ?>


    <script type='text/javascript' defer>

        

        const buttonList = document.querySelectorAll("[name=test]");

        buttonList.forEach((item ) => {
        
        item.addEventListener("mouseup", TimeOut);

        });

        function TimeOut(){
        setTimeout(function(){myMove()}, 300)
        };
    
        function myMove() {

            let id = null;

            const elem = document.getElementById("animate");   
            const elem2 = document.getElementById("animate2");
            const elem3 = document.getElementById("animate3");
            const elem4 = document.getElementById("animate4");   
            const elem5 = document.getElementById("animate5");   
            const elem6 = document.getElementById("animate6");   
            const elem7 = document.getElementById("animate7");  
            const elem8 = document.getElementById("animate8");   
 

   

            let mar = 1;
            let opa = 0;
            let opa2=100;
            let opa3=0;

            clearInterval(id);
            id = setInterval(frame, 5);

        function frame() {
        if (mar == 17) {
            clearInterval(id);
        } else {
            opa=opa+2;
            opa2=opa2-6.5;
            opa3=opa3+6.5;
            mar++; 

            elem.style.marginBottom = mar + "em";
            elem.style.opacity = opa2 + "%";
            elem2.style.opacity = opa + "%";
            elem3.style.opacity = opa3+"%";
            elem4.style.opacity = opa3+"%";
            elem5.style.opacity = opa3+"%";
            elem6.style.opacity = opa3+"%";
            elem7.style.opacity = opa3+"%"; 
            elem8.style.opacity = opa3+"%"; 



            

        }
        }
    };

    function loadFilm(clicked){

        id = clicked;

        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {           
            good= this.responseText;              
            good = good.toString().replace('\t', '');
            TabHead = good.toString().replace('\n', '').split('@');

            var node='';

            node = document.getElementById('node-id');

            node.innerHTML ='<img class="img-head" id="animate2" src="Image/'+TabHead[0]+'"><p class="p-head titre-head font" id="animate3">'+TabHead[1]+'</p><p class="p-head genre-head font"id="animate4">'+TabHead[3]+'</p><p class="p-head real-head font" id="animate5">'+TabHead[4]+'</p><p class="p-head acteur-head font" id="animate6">'+TabHead[5]+'</p><p class="p-head duree-head font" id="animate7">'+TabHead[6]+'</p><p class="p-head syno-head font" id="animate8">'+TabHead[7]+'</p>';
            console.log(node.innerHTML);
        }
;


        xhttp.open("POST", "test.php");
        xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhttp.send("id="+clicked);

        
    };
    
        

    </script>
</body>
</html>