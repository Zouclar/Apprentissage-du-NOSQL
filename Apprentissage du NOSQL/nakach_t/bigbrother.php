#!/usr/bin/env php
<?php
   // bigbrother.php for  in /home/nakach_t/Projet/nosql
   // 
   // Made by Nakache thomas
   // Login   <nakach_t@etna-alternance.net>
   // 
   // Started on  Mon Jan  5 15:46:29 2015 Nakache thomas
// Last update Fri Jan  9 17:28:26 2015 Nakache thomas
   //

setlocale (LC_ALL, 'fr-FR.utf8','fra');
$mongo = new MongoClient();
$data = $mongo->bdd;
$crea = $data->createCollection("eleves");
if (function_exists($argv[1]))
  {
    if (isset($argv[2]))
      $argv[1]($argv[2],$crea);
      else
	echo "Il manque le nom de le personne !\n";
  }
     else
       echo "il manque une fonction !\n\tVous Pouvez utiliser:\n\tadd_student\n\tdel_student\n\tupdate_student\n\tshow_student\n\tadd_comment\n";

function add_student($name,$crea)
{
  echo "nom ? (Complet)\n";
  $entry = fopen("php://stdin","r");
  echo "> ";
  $lol = fgets($entry);
  $nom = str_ireplace("\n", "","$lol");
  echo "promo ?\n";
  $entry = fopen("php://stdin","r");
  echo "> ";
  $lel = fgets($entry);
  $promo = str_ireplace("\n", "","$lel");
  echo "email ?\n";
  $entry = fopen("php://stdin","r");
  echo "> ";
  $kek = fgets($entry);
  $email = str_ireplace("\n", "","$kek");
  echo "telephone ?\n";
  $entry = fopen("php://stdin","r");
  echo "> ";
  $kok = fgets($entry);
  $telephone = str_ireplace("\n", "","$kok");
  echo "utilisateur ".$name." enregistre !\n";
  
  $array = array(
		 "login" => $name,
		 "name" => $nom,
		 "promo" => $promo,
		 "email" => $email,
  "telephone" => $telephone
		 );
  $crea->insert($array);
}

function del_student($name,$crea)
{
  echo "Etes-vous sur de vouloir supprimer ".$name."?\n";
  echo "> ";
  $entry = fopen("php://stdin","r");
  $rep = fgets($entry);
  $ponce = str_ireplace("\n", "","$rep");
  if ($ponce == "oui")
    {
      $crea->remove(array("login" => $name));
      echo "Utilisateur ".$name." supprime !\n";                                         
    }
      else
        {
          echo "Annule\n";
          exit;
        }
}

function show_student($name,$crea)
{
  echo "\n";
  $f = $crea->find(array("login" => $name));
  foreach($f as $lol) 
    {
      foreach($lol as $key => $val)
        {
	  if ($key != "_id") 
	    {
	      if ($key == "telephone" || $key == "commentaire")
		echo $key . "\t:   " . $val . "\n";
            else
              echo $key . "\t\t:   " . $val . "\n";
	    }
        }
    }
}

function update_student($name,$crea)
{
  echo "Que voulez vous modifier chez ".$name." ?\n";
  echo "> ";
  $entry = fopen("php://stdin","r");
  $rep = fgets($entry);
  $ponce = str_ireplace("\n", "","$rep");

  $f = $crea->find(array("login" => $name));
  
  foreach($f as $lol)
    {
      foreach($lol as $key => $val)
	{
	  if ($key == $ponce)
	    {
	      if ($key != "_id")
		{
		  echo "Vous voulez modifier ".$ponce."?\n>";
		  $test = fopen("php://stdin","r");
		  $mod = fgets($test);
		  $ponce = str_ireplace("\n", "","$mod");
		  $new = array('$set' => array($key => $ponce));
		  $crea->update(array("login" => $name), $new);
		  echo $key." bien enregistre pour ".$name."\n";
		}
	    }
	}
    }
}

function add_comment($name,$crea)
{
  $f = $crea->find(array("login" => $name));
  echo "Commentaire: ";
  $test = fopen("php://stdin","r");
  $mod = fgets($test);
  $ponce = str_ireplace("\n", "","$mod"); 
  foreach($f as $lol)
    {
      foreach($lol as $key => $val)
	{
	  if ($key == "commentaire")
	    {
	      $save = $val;
	    }
	  if (isset($save))
	    {
	      $ponce = "\n\t". $save . "\n".(strftime("%A %d %B"))." ----------- \n\t" . $ponce;
	      $new = array('$set' => array("commentaire" => $ponce));

	      $crea->update(array("login" => $name), $new);
	      echo $key." bien ajouté pour ".$name."\n";
	    }
        else
	  {
	    $new = array('$set' => array("commentaire" => $ponce));
	    $crea->update(array("login" => $name), $new);
	  }
	}
    }
}