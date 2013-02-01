<?php 
header('Content-Type: application/json; charset=utf-8');

try {
  $bdd = new PDO('mysql:host=localhost;dbname=proxy', 'USER', 'PASSWORD');
}
catch(Exception $e) {
  die('Erreur:' . $e->getMessage());
}


$url = $_GET['url'];

unset($_GET['url']);

foreach($_GET as $delta =>  $param) {
  $url .= "&$delta=$param";
}

if($url) {
  $req = $bdd->prepare('SELECT value FROM entry e WHERE e.key=?');
  $req->execute(array($url));
  $datas = $req->fetch();
  if (!$datas['value']) {
    $content = file_get_contents($url);
    $req = $bdd->prepare("INSERT INTO entry VALUES (?, ?)");
    $req->execute(array($url, $content));
    print($content);
  }else {
    print $datas['value'];
  }
}


?>