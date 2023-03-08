<?php
//exec('curl -v -c /usr/www/piersoft/prontosoccorsopugliabot/db/cookies.txt "https://www.sanita.puglia.it/web/asl-lecce/pronto-soccorso-accesso-in-tempo-reale" ');


//$html = file_get_contents("po.txt");

include("settings_t.php");
$text=$_GET["id"];
$gid=GDRIVEGID1;
$url="";
if (strpos($text,'BA_') !== false){
    $gid=GDRIVEGID3;
    $url="https://www.sanita.puglia.it/web/asl-bari/pronto-soccorso-accesso-in-tempo-reale";
    $text=str_replace("BA_","",$text);
}elseif (strpos($text,'BAT_') !== false){
    $text=str_replace("BAT_","",$text);
    $gid=GDRIVEGID2;
    $url="https://www.sanita.puglia.it/web/asl-barletta-andria-trani/pronto-soccorso-accesso-in-tempo-reale";

}elseif (strpos($text,'FG_') !== false){
    $gid=GDRIVEGID4;
    $url="https://www.sanita.puglia.it/web/asl-foggia/pronto-soccorso-accesso-in-tempo-reale";

    $text=str_replace("FG_","",$text);
}elseif (strpos($text,'LE_') !== false){
    $gid=GDRIVEGID1;
    $url="https://www.sanita.puglia.it/web/asl-lecce/pronto-soccorso-accesso-in-tempo-reale";

    $text=str_replace("LE_","",$text);
}elseif (strpos($text,'TA_') !== false){
    $gid=GDRIVEGID6;
    $url="https://www.sanita.puglia.it/web/asl-taranto/pronto-soccorso-accesso-in-tempo-reale";

      $text=str_replace("TA_","",$text);
}elseif (strpos($text,'BR_') !== false){
    $gid=GDRIVEGID5;
    $url="https://www.sanita.puglia.it/web/asl-brindisi/pronto-soccorso-accesso-in-tempo-reale";

      $text=str_replace("BR_","",$text);
}


//exec('curl -v -b db/cookies.txt -L "'.$url.'" -H "Host: www.sanita.puglia.it" -H "X-Requested-With: XMLHttpRequest" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36" -H "Content-Type: application/json; charset=UTF-8" -H "Accept: application/json, text/javascript, */*; q=0.01" -H "Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4,cs;q=0.2" -H "Accept-Encoding: gzip, deflate, sdch, br" --compressed -H "Referer: https://www.sanita.puglia.it/web/asl-lecce/pronto-soccorso-accesso-in-tempo-reale" -H "Connection: keep-alive" --data "{\""siglaProvincia\"":\""LE\"",\""codComune\"":\""E506\"",\""nomeComune\"":\""LECCE\"",\""startDate\"":\""01/07/2016\"",\""endDate\"":\""01/07/2016\"",\""checkNow\"":true,\""turni\"":[]}" > po.txt');

//  $html = file_get_contents($url);
$html = file_get_contents($url);
  $html=str_replace("<![CDATA[","",$html);
  $html=str_replace("]]>","",$html);
  $html=str_replace("","",$html);
  $html=str_replace("\n","",$html);
  $html=str_replace("&nbsp;","",$html);
  $html=str_replace(";"," ",$html);
  $html=str_replace(","," ",$html);


  $doc = new DOMDocument;
  $doc->loadHTML($html);

  $xpa    = new DOMXPath($doc);

  $divsl   = $xpa->query('//table[@class="tempiPS"]//div[@class="Titoli-news Titoli-news-home-19"]');
  //var_dump($divsl);
  $divs0   = $xpa->query('//div[2]/div/div/table/thead/tr/td');
  $divs1   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[2]/div');// //div[2]/div/div/table/tbody/tr[1]/td[5]/div
  $divs2   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[3]/div');
  $divs3   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[4]/div');
  $divs4   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[5]/div');//*[@id="div-pronto-soccorso"]/div[2]/div/div[4]/table/tbody/tr[1]/td[6]/div
  $divs5   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[6]/div');
  $divs6   = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[2]/div');//*[@id="div-pronto-soccorso"]/div[2]/div/div/table/tbody/tr[2]/td[2]/div
  $divs7   = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[3]/div');
  $divs8   = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[4]/div');
  $divs9   = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[5]/div');
  $divs10  = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[6]/div');
  $divs11  = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[2]/div');
  $divs12  = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[3]/div');
  $divs13   = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[4]/div');
  $divs14   = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[5]/div');
  $divs15   = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[6]/div');
  $divs16   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[2]/div');
  $divs17   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[3]/div');
  $divs18   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[4]/div');
  $divs19   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[5]/div');
  $divs23   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[6]/div');
  //intestazioni città
  $divs   = $xpa->query('//div[2]/div/div/table/tbody/tr[1]/td[1]');///div[2]/div/div/table/tbody/tr[1]/td[1]
  $divs20   = $xpa->query('//div[2]/div/div/table/tbody/tr[2]/td[1]');//div[2]/div/div/table/tbody/tr[2]/td[1]
  $divs21   = $xpa->query('//div[2]/div/div/table/tbody/tr[3]/td[1]');
  $divs22   = $xpa->query('//div[2]/div/div/table/tbody/tr[4]/td[1]');
  $dival=[];
  $diva0=[];
  $diva=[];
  $diva17=[];
  $diva18=[];
  $diva19=[];
  $diva20=[];
  $diva21=[];
  $diva22=[];
  $diva23=[];
  $diva1=[];
  $diva2=[];
  $diva3=[];
  $diva4=[];
  $diva5=[];
  $diva6=[];
  $diva7=[];
  $diva8=[];
  $diva9=[];
  $diva10=[];
  $diva11=[];
  $diva12=[];
  $diva13=[];
  $diva14=[];
  $diva15=[];
  $diva16=[];
  $count=0;
  foreach($divs0 as $div0) {
    $count++;
          array_push($diva0,$div0->nodeValue);
  }
//  echo "Count: ".$count."\n";

  foreach($divsl as $divl) {

        array_push($dival,$divl->nodeValue);
  }

    foreach($divs as $div) {
        array_push($diva,$div->nodeValue);

    }

    foreach($divs1 as $div1) {

          array_push($diva1,$div1->nodeValue);
    }
    foreach($divs2 as $div2) {

          array_push($diva2,$div2->nodeValue);
    }
    foreach($divs3 as $div3) {

          array_push($diva3,$div3->nodeValue);
    }
    foreach($divs4 as $div4) {

          array_push($diva4,$div4->nodeValue);
    }
    foreach($divs5 as $div5) {

          array_push($diva5,$div5->nodeValue);
    }
    foreach($divs6 as $div6) {

          array_push($diva6,$div6->nodeValue);
    }
    foreach($divs7 as $div7) {

          array_push($diva7,$div7->nodeValue);
    }
    foreach($divs8 as $div8) {

          array_push($diva8,$div8->nodeValue);
    }
    foreach($divs9 as $div9) {

          array_push($diva9,$div9->nodeValue);
    }
    foreach($divs10 as $div10) {

          array_push($diva10,$div10->nodeValue);
    }
    foreach($divs11 as $div11) {

          array_push($diva11,$div11->nodeValue);
    }
    foreach($divs12 as $div12) {

          array_push($diva12,$div12->nodeValue);
    }
    foreach($divs13 as $div13) {

          array_push($diva13,$div13->nodeValue);
    }
    foreach($divs14 as $div14) {

          array_push($diva14,$div14->nodeValue);
    }
    foreach($divs15 as $div15) {

          array_push($diva15,$div15->nodeValue);
    }

    foreach($divs16 as $div16) {

          array_push($diva16,$div16->nodeValue);
    }
    foreach($divs17 as $div17) {

          array_push($diva17,$div17->nodeValue);
    }
    foreach($divs18 as $div18) {

          array_push($diva18,$div18->nodeValue);
    }

    foreach($divs19 as $div19) {

          array_push($diva19,$div19->nodeValue);
    }
    foreach($divs20 as $div20) {

          array_push($diva20,$div20->nodeValue);
    }
    foreach($divs21 as $div21) {

          array_push($diva21,$div21->nodeValue);
    }
    foreach($divs22 as $div22) {

          array_push($diva22,$div22->nodeValue);
    }
    foreach($divs23 as $div23) {

          array_push($diva23,$div23->nodeValue);
    }
  //$count=3;

$option=[];
  for ($i=0;$i<$count;$i++){
  	$filter=strtoupper($diva0[$i]);
    if (strpos($filter,strtoupper($text)) !== false)
  	{
$alert.="Stato,utenti,utenti,utenti,utenti,utenti\n";
  $alert.= $diva[$i].",";
  $alert.= $diva1[$i].",".$diva2[$i].",".$diva3[$i].",".$diva4[$i].",".$diva5[$i]."\n";
    $alert.= $diva20[$i].",";
  $alert.= $diva6[$i].",".$diva7[$i].",".$diva8[$i].",".$diva9[$i].",".$diva10[$i]."\n";
    $alert.= $diva21[$i].",";
  $alert.= $diva11[$i].",".$diva12[$i].",".$diva13[$i].",".$diva14[$i].",".$diva15[$i]."\n";
    $alert.= $diva22[$i].",";
  $alert.= $diva16[$i].",".$diva17[$i].",".$diva18[$i].",".$diva19[$i].",".$diva23[$i]."\n";
  $alert.="\n";
  }
}
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
echo $alert;
 ?>
