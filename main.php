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
	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
if (strpos($text,'/start') === false ){
//	$text =str_replace("/","",$text);
}
if (strpos($text,'@prontosoccorsopugliabot') !== false) $text =str_replace("@prontosoccorsopugliabot ","",$text);
	if ($text == "/start" || $text == "Informazioni") {
		$img = curl_file_create('logo.png','image/png');
		$contentp = array('chat_id' => $chat_id, 'photo' => $img);
		$telegram->sendPhoto($contentp);
		$reply = "Benvenuto. Questo è un servizio automatico per gli accessi in tempo reale dei ".NAME." http://www.sanita.puglia.it/homepugliasalute. \n
		📕 -> Codice rosso: molto critico, pericolo di vita, priorità massima, accesso immediato alle cure\n
		📒 -> Codice giallo: mediamente critico, presenza di rischio evolutivo, possibile pericolo di vita\n
		📗 -> Codice verde: poco critico, assenza di rischi evolutivi, prestazioni differibili\n
		📂 -> Codice bianco: non critico, prestazioni differibili\n
		Questo bot è stato realizzato da @piersoft e non è collegato in alcun modo con salute.puglia.it. Il progetto e il codice sorgente sono liberamente riutilizzabili con licenza MIT.";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ",new_info,," .$chat_id. "\n";
		file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
	}elseif($text == "/Lecce" || $text == "Lecce"){

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
			array_push($optionf,["BAT_".$csv[$i][0]]);

		}
				$keyb = $telegram->buildKeyBoard($optionf, $onetime=false);
				$content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Clicca sul Pronto Soccorso di interesse]");
				$telegram->sendMessage($content);

	//	$this->create_keyboard_temp($telegram,$chat_id);
	//	exit;
}elseif($text == "/Foggia" || $text == "Foggia"){

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

			$gid=GDRIVEGID1;
			if (strpos($text,'BA_') !== false){
					$gid=GDRIVEGID3;
				$text=str_replace("BA_","",$text);
			}elseif (strpos($text,'BAT_') !== false){
						$text=str_replace("BAT_","",$text);
						$gid=GDRIVEGID2;
			}elseif (strpos($text,'FG_') !== false){
					$gid=GDRIVEGID4;
					$text=str_replace("FG_","",$text);
			}elseif (strpos($text,'LE_') !== false){
					$gid=GDRIVEGID1;
					$text=str_replace("LE_","",$text);
			}elseif (strpos($text,'TA_') !== false){
					$gid=GDRIVEGID6;
						$text=str_replace("TA_","",$text);
			}elseif (strpos($text,'BR_') !== false){
					$gid=GDRIVEGID5;
						$text=str_replace("BR_","",$text);
			}
			$text=strtoupper($text);
			$text=str_replace(" ","%20",$text);
			$text=str_replace("-","%2D",$text);


			$urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20&WHERE%20upper(A)%20like%20%27%25";
			$urlgd  .=$text;
			$urlgd  .="%25%27%20&key=".GDRIVEKEY."&gid=".$gid;
			$inizio=1;
			$homepage ="";

			$csv = array_map('str_getcsv',file($urlgd));
		//	$csv=str_replace(array("\r", "\n"),"",$csv);
			$count = 0;
			foreach($csv as $data=>$csv1){
				$count = $count+1;
			}

				for ($i=$inizio;$i<2;$i++){
					$homepage .="\n";
					$homepage .=$csv[$i][0]."\n\n";
					$homepage .=$csv[$i][1]."\n📕 ".$csv[$i][2]."\n📒 ".$csv[$i][3]."\n📗 ".$csv[$i][4]."\n📁 ".$csv[$i][5]."\n-----\n".$csv[$i][6]."\n📕 ";
					$homepage .=$csv[$i][7]."\n📒 ".$csv[$i][8]."\n📗 ".$csv[$i][9]."\n📁 ".$csv[$i][10]."\n-----\n".$csv[$i][11]."\n📕 ".$csv[$i][12]."\n📒 ";
					$homepage .=$csv[$i][13]."\n📗 ".$csv[$i][14]."\n📁 ".$csv[$i][15]."\n-----\n".$csv[$i][16]."\n📕 ".$csv[$i][17]."\n📒 ".$csv[$i][18]."\n📗 ";
					$homepage .=$csv[$i][19]."\n📁 ".$csv[$i][20]."\n";
					$homepage .="-----\n";
				}
				$chunks = str_split($homepage, self::MAX_LENGTH);
				foreach($chunks as $chunk) {
					$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>false);
					$telegram->sendMessage($content);
						}
					$log=$today. ",ricerca,".$text."," .$chat_id. "\n";
					file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

		$this->create_keyboard_temp($telegram,$chat_id);
		exit;
		}

	}

	function create_keyboard_temp($telegram, $chat_id)
	 {
			 $option = array(["Bari","BAT"],["Brindisi","Foggia"],["Lecce","Taranto"],["Informazioni"]);
			 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
			 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Fai la tua ricerca]");
			 $telegram->sendMessage($content);
	 }


}



	 ?>
