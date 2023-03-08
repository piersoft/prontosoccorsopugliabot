	<?php
/**
* Telegram Bot Pronto Soccorso Puglia Lic. CC-BY 4.0 art52 CAD, Powered by Francesco "Piersoft" Paolicelli
*/

include("Telegram.php");
include("settings_t.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	$inline_query = $update["inline_query"];
	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($inline_query,$telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($inline_query,$telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	if (strpos($inline_query["location"],'.') !== false){
		$trovate=0;
		$res=[];
		$id="";
		$i=0;
		$idx=[];
		$distanza=[];
		$id3="";
		$id1="";
		$inline="";
	$id=$inline_query['id'];
	$lat=$inline_query["location"]['latitude'];
	$lon=$inline_query["location"]['longitude'];


$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID7;
$inizio=0;
$homepage ="";

$csv = array_map('str_getcsv',file($urlgd));
//	$csv=str_replace(array("\r", "\n"),"",$csv);
$count = 0;
foreach($csv as $data=>$csv1){
$count = $count+1;
}
/*
if ($count == 0){
	$id3 = $telegram->InlineQueryResultLocation($id."/0", $lat,$lon, "Nessun PS entro 50km \nNo DAE near 50km from you");
	$res= array($id3);
	$content=array('inline_query_id'=>$inline_query['id'],'results' =>json_encode($res));
	$telegram->answerInlineQuery($content);
	exit;
}
*/
$alert="";
$optionf=array([]);
$c=0;
for ($i=$inizio;$i<$count;$i++){

$long10=floatval($csv[$i][4]);
$lat10=floatval($csv[$i][3]);
$theta = floatval($lon)-floatval($long10);
$dist =floatval( sin(deg2rad($lat)) * sin(deg2rad($lat10)) +  cos(deg2rad($lat)) * cos(deg2rad($lat10)) * cos(deg2rad($theta)));
$dist = floatval(acos($dist));
$dist = floatval(rad2deg($dist));
$miles = floatval($dist * 60 * 1.1515 * 1.609344);
$data=0.0;

$t=0;
if ($miles >=1){
$t=floatval(50);
$data1=number_format($miles, 2, '.', '');
$data =number_format($miles, 2, '.', '')." Km";
} else {
$t=floatval(50*1000);

$data1=number_format(($miles*1000), 0, '.', '');
$data =number_format(($miles*1000), 0, '.', '')." mt";

}


$csv[$i][100]= array("distance" => "value");

$csv[$i][100]= $miles;
$csv[$i][101]= array("distancemt" => "value");

$csv[$i][101]= $data;



	if ($data1 < $t)
	{
		$c++;
		$distanza[$i]['distanza'] =$csv[$i][100];
		$distanza[$i]['distanzamt'] =$csv[$i][101];
		$distanza[$i]['id'] =$csv[$i][0];
		$distanza[$i]['lat'] =$csv[$i][3];
		$distanza[$i]['lon'] =$csv[$i][4];
		$distanza[$i]['idpr'] =$csv[$i][5];
		$distanza[$i]['idprr'] =str_replace(" ","-",$csv[$i][5]);

		$map="https://www.openstreetmap.org/?mlat=".$csv[$i][3]."&mlon=".$csv[$i][4]."#map=19/".$csv[$i][3]."/".$csv[$i][4];
		$location =preg_replace('/\s+?(\S+)?$/', '', substr(trim($distanza[$i]['idprr']), 0, 600))."\ndista: ".$distanza[$i]['distanzamt'];

		$idx[$i] = $telegram->InlineQueryResultArticle($id."/".$i, $location, array('message_text'=>$distanza[$i]['idpr'],'disable_web_page_preview'=>true),"http://www.piersoft.it/prontosoccorsopugliabot/cr.png");
		//array_push($res,$idx[$i]);
		$distanza[$i]['ar']=$idx[$i];
		$i++;
	}


}
//echo $homepage;
if ($c >0){
	sort($distanza);
	for ($i=$inizio;$i<$c;$i++){
		array_push($optionf,[$distanza[$i]['idpr']]);
	array_push($res,$distanza[$i]['ar']);
	//	if ($distanza[$i]['distanzamt'] !== null)
	//	$alert .=$distanza[$i]['idpr']."\nDista: ".$distanza[$i]['distanzamt']."\n------\n";
	}
}else{
	$id3 = $telegram->InlineQueryResultLocation($id."/0", $lat,$lon, "Nessun PS nei 50km da te \nNo PS near 50km from you");
	$res= array($id3);
}

$content=array('inline_query_id'=>$inline_query['id'],'results' =>json_encode($res));
$telegram->answerInlineQuery($content);

	}
if (strpos($text,'/start') === false ){
//	$text =str_replace("/","",$text);
}
//if (strpos($text,'@prontosoccorsopugliabot') !== false) $text =str_replace("@prontosoccorsopugliabot ","",$text);
	if ($text == "/start" || $text == "Informazioni") {
		$img = curl_file_create('logo.png','image/png');
		$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		$telegram->sendPhoto($contentp);
		$reply = "Benvenuto. Questo è un servizio automatico per gli accessi in tempo reale dei ".NAME." http://www.sanita.puglia.it/homepugliasalute. \n
		📕 -> Codice rosso: EMERGENZA\n
		📒 -> Codice arancione: URGENZA\n
		📘 -> Codice verde: URGENZA DIFFERIBILE\n
		📗 -> Codice verde: URGENZA MINORE\n
		📂 -> Codice bianco: NON URGENZA\n
Questo bot non è collegato in alcun modo con salute.puglia.it. Il progetto e il codice sorgente sono liberamente riutilizzabili con licenza MIT. Le coordinate dei P.S. sono state ricavate dal DB di openStreetMap con licenza odbl.
\nPer la mappa dei Pronti Soccorso --> http://u.osmfr.org/m/78971/ .\n";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ",new_info,," .$chat_id. "\n";
		file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
	}elseif ($text == "/Più vicini" || $text == "Più vicini") {
		$reply = "Invia la tua posizione tramite la 📎 per avere i Pronto Soccorso nel raggio di 50km. Ti consigliamo di controllare anche su http://www.sanita.puglia.it/web/pugliasalute/pronto-soccorso-primo-intervento nel caso ci siano punti di Pronto Intervento stagionali che potrebbero essere attivi";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);

	}

		elseif($location != null)
		{

			  			$lon=$location["longitude"];
			  			$lat=$location["latitude"];

			$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);

			$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID7;
			$inizio=0;
			$homepage ="";

			$csv = array_map('str_getcsv',file($urlgd));
		//	$csv=str_replace(array("\r", "\n"),"",$csv);
			$count = 0;
			foreach($csv as $data=>$csv1){
				$count = $count+1;
			}

			$alert="";
				$optionf=array([]);
				$c=0;
			for ($i=$inizio;$i<$count;$i++){

			$long10=floatval($csv[$i][4]);
			$lat10=floatval($csv[$i][3]);
			$theta = floatval($lon)-floatval($long10);
			$dist =floatval( sin(deg2rad($lat)) * sin(deg2rad($lat10)) +  cos(deg2rad($lat)) * cos(deg2rad($lat10)) * cos(deg2rad($theta)));
			$dist = floatval(acos($dist));
			$dist = floatval(rad2deg($dist));
			$miles = floatval($dist * 60 * 1.1515 * 1.609344);
			$data=0.0;

			$t=0;
			if ($miles >=1){
				$t=floatval(50);
				$data1=number_format($miles, 2, '.', '');
			  $data =number_format($miles, 2, '.', '')." Km";
			} else {
				$t=floatval(50*1000);

				$data1=number_format(($miles*1000), 0, '.', '');
				$data =number_format(($miles*1000), 0, '.', '')." mt";

			}


			  $csv[$i][100]= array("distance" => "value");

			  $csv[$i][100]= $data1;
			  $csv[$i][101]= array("distancemt" => "value");

			  $csv[$i][101]= $data;



			      if ($data1 < $t)
			      {
							$c++;
			        $distanza[$i]['distanza'] =$csv[$i][100];
			        $distanza[$i]['distanzamt'] =$csv[$i][101];
			        $distanza[$i]['id'] =$csv[$i][0];
			        $distanza[$i]['lat'] =$csv[$i][3];
			        $distanza[$i]['lon'] =$csv[$i][4];
							$distanza[$i]['idpr'] =$csv[$i][7];

			      }


			}
			//echo $homepage;

			sort($distanza);
		//	sort($optionf);


			for ($i=$inizio;$i<$c;$i++){
				array_push($optionf,[$distanza[$i]['idpr']]);

				if ($distanza[$i]['distanzamt'] !== null) $alert .=$distanza[$i]['id']."\nDista: ".$distanza[$i]['distanzamt']."\n------\n";
			}
			$telegram->buildKeyBoardHide(true);
			$telegram->buildForceReply(true);
			$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
			$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $alert);
			$telegram->sendMessage($content);

		//	$chunks = str_split($alert, self::MAX_LENGTH);
		//	foreach($chunks as $chunk) {
		//		$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>false);
		//		$telegram->sendMessage($content);
		//			}


		}
		elseif($text == "/Lecce" || $text == "Lecce"){
		$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID0;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
			array_push($optionf,["LE_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/Brindisi" || $text == "Brindisi"){
	$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID5;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
			array_push($optionf,["BR_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/Taranto" || $text == "Taranto"){
	$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID6;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
			array_push($optionf,["TA_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/Bari" || $text == "Bari"){
	$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID3;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
			array_push($optionf,["BA_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/BAT" || $text == "BAT"){
	$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID2;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
		//	$csv[$i][0]=str_replace("__"," ",$csv[$i][0]);
			array_push($optionf,["BAT_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/Foggia" || $text == "Foggia"){
	$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
	$telegram->sendMessage($content);
		$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20A%20&key=".GDRIVEKEY."&gid=".GDRIVEGID4;
		$inizio=1;
		$homepage ="";

		$csv = array_map('str_getcsv',file($urlgd));
	//	$csv=str_replace(array("\r", "\n"),"",$csv);
		$count = 0;
		foreach($csv as $data=>$csv1){
			$count = $count+1;
		}
		$optionf=array([]);
		for ($i=$inizio;$i<$count;$i++){
			array_push($optionf,["FG_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
	}
		elseif(strpos($text,'_') !== false)
		{

			$text=str_replace("__",". ",$text);
			$content = array('chat_id' => $chat_id, 'text' => "Elaborazione, attendere...",'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
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
					if ($text =="PO SAN GIOVANNI ROTONDO"){
						$reply = "Non è un PO che fornisce ad oggi la coda d'attesa";
						$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
						$telegram->sendMessage($content);
						$this->create_keyboard_temp($telegram,$chat_id);
						exit;
					}
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

	//exec('curl -v -c log/cookies.txt "'.$url.'" ');
	//exec('curl -v -b log/cookies.txt -L "'.$url.'" -H "Host: www.sanita.puglia.it" -H "X-Requested-With: XMLHttpRequest" -H "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36" -H "Content-Type: application/json; charset=UTF-8" -H "Accept: application/json, text/javascript, */*; q=0.01" -H "Accept-Language: it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4,cs;q=0.2" -H "Accept-Encoding: gzip, deflate, sdch, br" --compressed -H "Referer: http://www.sanita.puglia.it/" -H "Connection: keep-alive" > log/po.txt');
	$html = file_get_contents($url);


  //$html = file_get_contents('log/po.txt');

  $html=str_replace("<![CDATA[","",$html);
  $html=str_replace("]]>","",$html);
  $html=str_replace("</br>","",$html);
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
	//  echo "Count: ".$count."\n</br>";

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
	  $alert.=$dival[$i]."\n".$diva0[$i]."\n";
	  $alert.= $diva[$i].":\n📕 ";
	  $alert.= $diva1[$i]." 📒 ".$diva2[$i]." 📘 ".$diva3[$i]." 📗 ".$diva4[$i]." 📁 ".$diva5[$i]."\n";
	    $alert.= $diva20[$i].":\n📕 ";
	  $alert.= $diva6[$i]." 📒 ".$diva7[$i]." 📘 ".$diva8[$i]." 📗 ".$diva9[$i]." 📁 ".$diva10[$i]."\n";
	    $alert.= $diva21[$i].":\n📕 ";
	  $alert.= $diva11[$i]." 📒 ".$diva12[$i]." 📘 ".$diva13[$i]." 📗 ".$diva14[$i]." 📁 ".$diva15[$i]."\n";
	    $alert.= $diva22[$i].":\n📕 ";
	  $alert.= $diva16[$i]." 📒 ".$diva17[$i]." 📘 ".$diva18[$i]." 📗 ".$diva19[$i]." 📁 ".$diva23[$i]."\n";
	  $alert.="\n";
	  }
	}
$chunks = str_split($alert, self::MAX_LENGTH);
foreach($chunks as $chunk) {
	$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>false);
	$telegram->sendMessage($content);
		}
		$text=str_replace(" ","%20",$text);
		$text=str_replace("-","%2D",$text);
		if (strpos($text,'\'') !== false){
			$text=str_replace("'","_",$text);
		}

		$urlgd1  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20A%20like%20%27%25";
		$urlgd1 .=strtoupper($text);
		$urlgd1  .="%25%27&key=".GDRIVEKEY."&gid=".GDRIVEGID7;
		$inizio=1;
		$csv1 = array_map('str_getcsv',file($urlgd1));
		$count1 = 0;

	//	$contentl1 = array('text' => "Po: ".	$urlgd1,'chat_id' => $chat_id);
	//	$telegram->sendMessage($contentl1);

		foreach($csv1 as $data1=>$csv11){
			$count1 = $count1+1;
		}
			for ($i=$inizio;$i<2;$i++){
						$contentl = array('text' => "Po: ".$csv1[1][0],'chat_id' => $chat_id, 'latitude' => $csv1[1][3],'longitude'=> $csv1[1][4]);
						$telegram->sendLocation($contentl);

				//		$contentl1 = array('text' => "Po: ".	$urlgd1,'chat_id' => $chat_id);
				//		$telegram->sendLocation($contentl1);
			}

						$log=$today. ",ricerca,".$text."," .$chat_id. "\n";
						file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
		}

	}

	function create_keyboard_temp($telegram, $chat_id)
	 {
			 $option = array(["Bari","BAT"],["Brindisi","Foggia"],["Lecce","Taranto"],["Più vicini","Informazioni"]);
			 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
			 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Fai la tua ricerca.]");
			 $telegram->sendMessage($content);
	 }



}



	 ?>
