<?php
  // modification de <? en <?php seul script ayant encore cette notation dépréciée. GR 31/10/19
  //Si on a toujours pas d'id de session, on le cree avec le cookie
  //Duree d'une session (3600 = 1 heure)
  $session_duree = "31536000";

  $session_chr = array(
                        0 => 48,
                        1 => 49,
                        2 => 50,
                        3 => 51,
                        4 => 52,
                        5 => 53,
                        6 => 54,
                        7 => 55,
                        8 => 56,
                        9 => 57,
                        10 => 65,
                        11 => 66,
                        12 => 67,
                        13 => 68,
                        14 => 69,
                        15 => 70,
                        16 => 71,
                        17 => 72,
                        18 => 73,
                        19 => 74,
                        20 => 75,
                        21 => 76,
                        22 => 77,
                        23 => 78,
                        24 => 79,
                        25 => 80,
                        26 => 81,
                        27 => 82,
                        28 => 83,
                        29 => 84,
                        30 => 85,
                        31 => 86,
                        32 => 87,
                        33 => 88,
                        34 => 89,
                        35 => 90,
                        36 => 97,
                        37 => 98,
                        38 => 99,
                        39 => 100,
                        40 => 101,
                        41 => 102,
                        42 => 103,
                        43 => 104,
                        44 => 105,
                        45 => 106,
                        46 => 107,
                        47 => 108,
                        48 => 109,
                        49 => 110,
                        50 => 111,
                        51 => 112,
                        52 => 113,
                        53 => 114,
                        54 => 115,
                        55 => 116,
                        56 => 117,
                        57 => 118,
                        58 => 119,
                        59 => 120,
                        60 => 121,
                        61 => 122);

  srand((float) microtime()*1000000);

  //Generation de l'id de session tout en verifiant qu'il est unique
  do
  {
    $session_id = "";

    for($i=0;$i<29;$i++)
    {
      $randval = rand(0, count($session_chr)-1);
      $session_id .= chr($session_chr[$randval]);
    }
    $session_query = "SELECT sessionid
                      FROM utilisateur
                      WHERE sessionid = '$session_id';";
    $session_result = mysqli_query($link,$session_query);
  }
  while(mysqli_num_rows($session_result) != 0);

  // Duree de validite de la session
  $session_expire = date("Y-m-d H:i:s", time() + $session_duree);

  if (!isset($idmembre))
    $idmembre = '0';

  // Ajout de la session a la table utilisateur
  $session_query = "INSERT INTO utilisateur(id_utilisateur,
                                            id_membre,
                                            sessionid,
                                            dateexpire)
                    VALUES(
                    '',
                    '" . $idmembre . "',
                    '$session_id',
                    '$session_expire'
                    )
                    ;";
  $session_result = mysqli_query($link,$session_query);

  //on stock l'id de la session dans une variable de session
  $_SESSION["sessionid"] = $session_id;

  //on sauve aussi dans un cookie
  $session_expire = strtotime($session_expire);
	$domain = ereg_replace('www2', '', $_SERVER['SERVER_NAME']);
	$domain = ereg_replace('www', '', $domain);
//	echo $domain;
  setcookie("sessionid", $_SESSION["sessionid"], $session_expire, "/", $domain);

?>
