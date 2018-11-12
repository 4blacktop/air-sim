<?php
// переменные main
$img_width = 400;
$sitename = "##Летные симуляторы\n";
$keywords_main = "@@keywords=флайт симулятор полет самолет ВС тип миссии аддоны майкрософт msfs 2002 2004 x\n";
$description_main = "@@description=На сайте размещена информация по широко распространенным флайт-симуляторам, включая пакеты дополнений, карты, миссии, маршруты и различные типы воздушных судов.\n";
$publish_main = "2010-05-22";

// переменные UV2
$keywords_UV2 = "@@keywords=раздел $genre модель тренажер самолет вертолет миссия аэропорт глиссада\n";
$description_UV2 = "@@description=раздел летных симуляторов $genre\n";
$publish_UV2 = "2010-05-23";
$slogan_UV2 = "\nПопулярная статья:\n";

// переменные UV3
$keywords_UV3 = "@@keywords=информация описание $title раздел $genre РУД педали аэродинамика элерон руль высоты модель\n";
$description_UV3 = "@@description=характеристики $title из раздела флайт симуляторов $genre.\n";
$publish_this_year = 2010;
$publish_this_year_month_start = 6;
$publish_this_year_month_end = 9;
$publish_future_year_end = 2015;

// вывод хэдера для броузера
header("Content-Type: text/html; charset=utf-8"); 

// перекодировка транслит
$translit = array("\xd1\x91"=>"e","\xd0\xb9"=>"y","\xd1\x86"=>"ts","\xd1\x83"=>"u","\xd0\xba"=>"k","\xd0\xb5"=>"e","\xd0\xbd"=>"n","\xd0\xb3"=>"g","\xd1\x88"=>"sh","\xd1\x89"=>"shch","\xd0\xb7"=>"z","\xd1\x85"=>"kh","\xd1\x8a"=>"","\xd1\x84"=>"f","\xd1\x8b"=>"y","\xd0\xb2"=>"v","\xd0\xb0"=>"a","\xd0\xbf"=>"p","\xd1\x80"=>"r","\xd0\xbe"=>"o","\xd0\xbb"=>"l","\xd0\xb4"=>"d","\xd0\xb6"=>"zh","\xd1\x8d"=>"e","\xd1\x8f"=>"ya","\xd1\x87"=>"ch","\xd1\x81"=>"s","\xd0\xbc"=>"m","\xd0\xb8"=>"i","\xd1\x82"=>"t","\xd1\x8c"=>"","\xd0\xb1"=>"b","\xd1\x8e"=>"yu","\xd0\x81"=>"E","\xd0\x99"=>"Y","\xd0\xa6"=>"TS","\xd0\xa3"=>"U","\xd0\x9a"=>"K","\xd0\x95"=>"E","\xd0\x9d"=>"N","\xd0\x93"=>"G","\xd0\xa8"=>"SH","\xd0\xa9"=>"SHCH","\xd0\x97"=>"Z","\xd0\xa5"=>"KH","\xd0\xaa"=>"","\xd0\xa4"=>"F","\xd0\xab"=>"Y","\xd0\x92"=>"V","\xd0\x90"=>"A","\xd0\x9f"=>"P","\xd0\xa0"=>"R","\xd0\x9e"=>"O","\xd0\x9b"=>"L","\xd0\x94"=>"D","\xd0\x96"=>"ZH","\xd0\xad"=>"E","\xd0\xaf"=>"YA","\xd0\xa7"=>"CH","\xd0\xa1"=>"S","\xd0\x9c"=>"M","\xd0\x98"=>"I","\xd0\xa2"=>"T","\xd0\xac"=>"","\xd0\x91"=>"B","\xd0\xae"=>"YU",);
// запускаем таймер выполнения скрипта
set_time_limit(0);
ini_set('memory_limit', '512M');
// error_reporting(0);
$mtime = microtime(true);
// кол-во страниц УВ2, УВ3, УВ4
$kolvoUV2 = 0;
$kolvoUV3 = 0;
$kolvoUV4 = 0;
// путь для парсера
$path = realpath('d:/rt/');


//*******************************************************************************
//************************ НАЧАЛО ПЕРВОГО ПРОХОДА *******************************
//*******************************************************************************

// открываем файл с анкорами для записи
$anc=fopen("anchors.txt","w");
// запускаем итератор
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
// обрабатываем массив
foreach($objects as $object)
	{
	// если перед нами каталог, делаем страницу УВ2
	if ($object->isDir())
		{
		// получаем название раздела (жанра) из имени текущего каталога
		$genre = $objects->getFilename();
		// транслит $genre
		$genre = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$genre);
		$genre = iconv("windows-1251", "UTF-8//IGNORE", $genre);
		$genre_translit = strtolower(strtr($genre, $translit));
		$genre_translit = preg_replace('%&.+?;%', '', $genre_translit);
		$genre_translit = preg_replace('%[^a-z0-9,._-]+%', '-', $genre_translit);
		$genre_translit = trim($genre_translit, '-');
		
		
		}
	// если файл - проходим страницу УВ3
	else
		{
		// переменная $file - это текущий файл
		// перменная $filename - это его имя
		$file = $object->getFilename();
		$filename = basename($file, ".html");
		// читаем контент и преобразуем его
		$content = file_get_contents($object);
		$content = preg_replace('~\&\#.*?\;~si', '', $content);
		
		
		// читаем тайтл и преобразуем его
		preg_match("'<title[^>]*?>.*?</title>'",$content, $title);
		$title = implode('',$title);
		// замена незнаючего
		$title = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$title);
		// замена от символа «/» до конца строки
		$title = preg_replace("'\/.*?$'si", "", $title);
		// замена "скобка_любыесимволы_скобка"
		$title = preg_replace("'\(.*?\)'si", "", $title);
		// замена "квскобка_любыесимволы_квскобка"
		$title = preg_replace("'\[.*?\]'si", "", $title);
		// замена "фигскобка_любыесимволы_фигскобка"
		$title = preg_replace("'\{.*?\}'si", "", $title);
		// замена "пробел_тире_пробел_конецстроки"
		$title = preg_replace("'\s\-\s$'si", "", $title);
		// замена "началостроки_пробел_тире_пробел"
		$title = preg_replace("'^\s\-\s'si", "", $title);
		// замена "началостроки_тире_пробел"
		$title = preg_replace("'^-'si", "", $title);
		// удаляем повторяющиеся пробелы
		$title= chop ($title);
		// удаляем пробелы в начале и в конце
		$title = trim($title);
		// удаляем одиночные скобки различного характера
		$title = str_ireplace("[", "", $title);
		$title = str_ireplace("]", "", $title);
		$title = str_ireplace("(", "", $title);
		$title = str_ireplace(")", "", $title);
		$title = str_ireplace("{", "", $title);
		$title = str_ireplace("}", "", $title);
		$title = str_ireplace("- ", "", $title);
		
		
		
		
		
		
		
		
		
		
		// транслит $title
		// $title = iconv("windows-1251", "UTF-8//IGNORE", $title);
		$title_translit = strtolower(strtr($title, $translit));
		$title_translit = preg_replace('%&.+?;%', '', $title_translit);
		$title_translit = preg_replace('%[^a-z0-9,._-]+%', '-', $title_translit);
		$title_translit = trim($title_translit, '-');
		$url2uv_translit=$genre_translit."/".$title_translit;
		$str_url2uv_translit = strval($url2uv_translit);
		//пишем файл с анкорами
		$str_anc = $title;
		$str_anc = preg_replace("'\/.*?$'si","",$str_anc);
		fwrite($anc,"$str_anc\n");
		// заносим в массив урлов только файлы, которые меньше 7
		// переменная $last_1 - это последняя цифра $startfile для первого прохода
		// цифра 7 - для УВ4
		// цифры 8 и 9 - для отложенной публикации
		$str_startfile_1=strval($filename);
		$last_1 = $str_startfile_1{strlen($str_startfile_1)-1};
		if(($last_1 < 7))
			{
			$array_url[] = $str_url2uv_translit.".html";
			}
		}
	}
// закрываем файл с анкорами
fclose($anc);
	
	
//*******************************************************************************
//************************* КОНЕЦ ПЕРВОГО ПРОХОДА *******************************
//*******************************************************************************


//*******************************************************************************
//************************ НАЧАЛО ВТОРОГО ПРОХОДА *******************************
//*******************************************************************************


// эти переменные - счетчики кол-ва страниц, публикуемых в соответствующих годах
$kolvo2010 = 0;
$kolvo2011 = 0;
$kolvo2012 = 0;
$kolvo2013 = 0;
$kolvo2014 = 0;
$kolvo2015 = 0;
// открываем файл для записи
$dirname = "z:/home/test3.ru/www/tools/";
$pages=fopen("pages.txt","w");
$dir = opendir($dirname);
// запускаем итератор
$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);

// пишем главную страницу

$z=0;
$rndmin = rand (10,59);
$rndhour = rand (1,11);
$rndsec = rand (10,59);
if($rndhour < 10) {$rndhour=$z.$rndhour;}
fwrite($pages,$sitename);
fwrite($pages,"@@file=index\n");
fwrite($pages,$keywords_main);
fwrite($pages,$description_main);
fwrite($pages,"@@publish=$publish_main $rndhour:$rndmin:$rndsec\n");
fwrite($pages,"@@nomenuitem=1\n");
fwrite($pages,"@@template.zhtml=main\n");
fwrite($pages,"@@filter=zfilter_php, zfilter_text2html\n");
fwrite($pages,"\n\n
Летный симулятор - это искусственное воссоздание полета воздушного судна и различных сопуствтвующих летных процессов, включая аэродинмаическое моделирование, реакцию на управление и воздействие других систем в разрезе таких категорий, как плотность воздуха, турбулентность, облачность и атмосферное давление. Симуляторы широко используются для летных тренировок (в основном, пилотов), для моделирования конструкции и создания дизайна, исследования полетных характеристик, управляемости и так далее.\n
\n\n");
fwrite($pages,'<img src="~/images/air.jpg" alt="флайт" title="flight" align="center" />');
fwrite($pages,"\n\n<?php include('sape1.php');?>\n\n
Летные симуляторы в зависимости от назначени ямогут быть основаны на различном аппаратном обеспечении, от этого зависит глубина детализации и реализма: начиная от платформ персональных компьютеров, которые копируют кабину пилотов для ознакомления, и до более комплексных кабин-симуляторов с полноценными органами управления и широкоформатным обзором, смонтированных на движущихся платформах с шестью степенями свободы, которые откликаются на любое движение плиота и моделируют внешние аэродинамические факторы.\n
\n\n");
fwrite($pages,'<img src="~/images/sim.jpg" alt="симулятор" title="simulator" align="center" />');
fwrite($pages,"\n\n<?php include('sape2.php');?>\n\n
Последние модели применяются в авиационной отрасли для обучения пилотов и других членов экипажа для гражданской и военной авиации, кроме того, существуют тренажеры для обслуживающиго персонала. Симуляторы могут быть узко- или широкоспециализированными: начиная от простых тренажеров для отработки действий в нештатных ситуациях и общей схемы полета, и заканчивая полноценным моделированием полета, в случае военных самолетов, они включают применение оружия.\n
Практически любой может попробовать свои силы в Москве на тренажере Боинг-737, однако это не такое дешевое удовольствие: порядка 300 долларов в час, поэтому симмеры предпочитают ставить MSFS или ему подобные компьютерные программы. Детище Microsoft - одно из долгожителей, оно выпускается с 1976 г., и оно на три года старше WIndows. В 1977 перые версии были проданы для платформ 8080 и Atari, в 1979 - для Apple, а первая лицензия на IBM PC появилась в 1982 году с графикой CGA. Итогом развития серии стали Microsoft Flight Simulator 2004 и Microsoft Flight Simulator X. Это больше не игра, а в силу реализма комплексный и достаточно трудный в освоении симулятор полета, в награду за освоенние которого полагается звание - опытный симмер. Полеты проходят над всей поверхностью земного шара, в различной детализации и охватывают более 24 тысяч аэропортов. Индивидуальные сценарии включают растущее на глазах большое количество небольших населенных пунктов и региональных аэропртов. Детальность ландшафта немного снижается вдали от крупных населенных пунктов, особенно вне территории США, хотя опять же на многих сайтах есть дополнения (платные и бесплатные), чтобы компенсировать этот изъян.\n
В старших версиях флайт-симулятора от Microsoft добавлена интересная деталь - моделирование погоды по реальным данным на текущий момент, кроме того внедрен воздушное движение опять же, обновляющееся в реальном времени. Ассортимент летательных аппаратов огромен - начиная от DC-3 и заканчивая Boeing 777, к вашим услугам также интерактивные уроки, включая чеклист - карту проверок. К сожалению, 22 января 2009 года Microsoft объявила о закрытии подразделения ACES Game Studio, ответственного за линейку симулятора, поэтому его будущее развитие туманно, несмотря на заявления отдельных сотрудников о разработке независимого продукта, основанного на существующих наработках в этой области.\n
\n\n");
// как будто прошлая страница - была УВ1, чтобы не записать после нее УВ4
$last_add_uv = 1;

// обрабатываем массив
foreach($objects as $object)
	{
	// если перед нами каталог, делаем страницу УВ2
	if ($object->isDir())
		{
		// создаваемая страница будет УВ2, поэтому флаг ставим в 1, чтобы не записать после нее УВ4
		$last_add_uv = 2;
		// конвертируем кодировку
        // echo iconv("windows-1251", "UTF-8//IGNORE", "<br /><strong>".$objects->getFilename() . "</strong>");
		// увеличиваем счетчик УВ2
		$kolvoUV2++;
		// получаем название раздела (жанра) из имени текущего каталога
		$genre = $objects->getFilename();
		// транслит $genre
		$genre = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$genre);
		$genre = iconv("windows-1251", "UTF-8//IGNORE", $genre);
		$genre_translit = strtolower(strtr($genre, $translit));
		$genre_translit = preg_replace('%&.+?;%', '', $genre_translit);
		$genre_translit = preg_replace('%[^a-z0-9,._-]+%', '-', $genre_translit);
		$genre_translit = trim($genre_translit, '-');

		// пишем страницу УВ2 с жанром
		$z=0;
		$rndmin = rand (10,59);
		$rndhour = rand (1,11);
		$rndsec = rand (10,59);
		if($rndhour < 10) {$rndhour=$z.$rndhour;}
		fwrite($pages,"##$genre\n");
		fwrite($pages,$keywords_UV2);
		fwrite($pages,$description_UV2);
		fwrite($pages,"@@publish=$publish_UV2 $rndhour:$rndmin:$rndsec\n");
		fwrite($pages,"@@nosubmenu=true\n");
		fwrite($pages,"@@donotlist=1\n");
		fwrite($pages,"@@params.perpage=10000\n");
		fwrite($pages,"@@filter=zfilter_php, zfilter_text2html\n");
		fwrite($pages,"<strong>$slogan_UV2 $genre:</strong>\n\n");	
		
		echo "<br /><br /><strong>$genre $publish_UV2 $rndhour:$rndmin:$rndsec</strong>";
		}
	// если файл - пишем страницу УВ3
	else
		{
		
		
		// переменная $file - это текущий файл
		// перменная $filename - это его имя
		$file = $object->getFilename();
		$filename = basename($file, ".html");
		
		// читаем контент и преобразуем его
		$content = file_get_contents($object);
		$content = preg_replace('~\&\#.*?\;~si', '', $content);
		
		
		// читаем тайтл и преобразуем его
		preg_match("'<title[^>]*?>.*?</title>'",$content, $title);
		$title = implode('',$title);
		// замена незнаючего
		$title = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$title);
		// замена от символа «/» до конца строки
		$title = preg_replace("'\/.*?$'si", "", $title);
		// замена "скобка_любыесимволы_скобка"
		$title = preg_replace("'\(.*?\)'si", "", $title);
		// замена "квскобка_любыесимволы_квскобка"
		$title = preg_replace("'\[.*?\]'si", "", $title);
		// замена "фигскобка_любыесимволы_фигскобка"
		$title = preg_replace("'\{.*?\}'si", "", $title);
		// замена "пробел_тире_пробел_конецстроки"
		$title = preg_replace("'\s\-\s$'si", "", $title);
		// замена "началостроки_пробел_тире_пробел"
		$title = preg_replace("'^\s\-\s'si", "", $title);
		// замена "началостроки_тире_пробел"
		$title = preg_replace("'^-'si", "", $title);
		// удаляем повторяющиеся пробелы
		$title= chop ($title);
		// удаляем пробелы в начале и в конце
		$title = trim($title);
		// удаляем одиночные скобки различного характера
		$title = str_ireplace("[", "", $title);
		$title = str_ireplace("]", "", $title);
		$title = str_ireplace("(", "", $title);
		$title = str_ireplace(")", "", $title);
		$title = str_ireplace("{", "", $title);
		$title = str_ireplace("}", "", $title);
		$title = str_ireplace("- ", "", $title);
		
		
		
		
		
		
		
		
		
		
		// читаем контент и преобразуем его
		$content = file_get_contents($object);
		// удаляем тире в начале строки
		$content = preg_replace("/\n[\-]/si", "", $content);
		// удаляем html-коды
		$content = preg_replace('~\&.*?\;~si', '', $content);
		// удаляем мусор
		$content = str_ireplace("#", "", $content);
		$content = str_ireplace('<h3 class="sp-title">', "", $content);
		$content = str_ireplace("</h3>", "", $content);
		$content = str_ireplace("Релиз группы:", "", $content);
		$content = preg_replace("'<title[^>]*?>.*?</title>'", '', $content);
		$content = preg_replace("'<img.class\=[^>].*?/>'", '', $content);
		$content = str_ireplace("<img", "<img width=\"$img_width\"", $content);		
		// для serialatino.ru
		$content = str_ireplace("Год выпуска", "Дата съемки", $content);
		$content = str_ireplace("Русские субтитры", "Наличие субтитров", $content);
		$content = str_ireplace("Перевод", "Информация о переводе", $content);
		$content = str_ireplace("Продолжительность", "Длина", $content);
		$content = str_ireplace("В ролях", "Актеры", $content);
		$content = str_ireplace("Описание", "Содержание", $content);
		$content = str_ireplace("Доп. информация", "Подробнее", $content);
		$content = str_ireplace("Качество", "Quality", $content);
		$content = str_ireplace("Формат", "Информация", $content);
		$content = str_ireplace("Видео", "Video", $content);
		$content = str_ireplace("Аудио", "Audio", $content);
		$content = str_ireplace("Скриншоты", "Постер", $content);
		
		// для mnogomult.ru
		$content = str_ireplace("Вид битрейта", "Битрейт, тип", $content);
		$content = str_ireplace("Размер файла", "Длина файла", $content);
		$content = str_ireplace("Идентификатор", "Тег идентификатора", $content);
		$content = str_ireplace("Соотношение кадра", "Формат", $content);
		$content = str_ireplace("Частота кадров", "Кадров в секунду", $content);
		$content = str_ireplace("Расположение каналов", "Аудиоканалы", $content);
		$content = str_ireplace("Внимание! В случае закрытия, релиз будет выложен еще раз тут или в другом месте, желающие получат альтернативный адрес приватным сообщением.", "", $content);
		$content = str_ireplace("Раздачи на rutracker.org (torrents.ru) - ", "", $content);
		$content = str_ireplace("Раздачи (под тем же ником) на другом, свободном от правообладателей трекере, где нужна регистрация и действует рейтинговая система - адрес личным сообщением.", "", $content);
		$content = str_ireplace("Активность правообладателей растет - рекомендую запастись рейтингом на резервном, свободном трекере.", "", $content);
		$content = str_ireplace("[", "", $content);
		$content = str_ireplace("]", "", $content);
		$content = str_ireplace("", "", $content);
		$content = str_ireplace("", "", $content);
		$content = str_ireplace("", "", $content);
		// $content = str_ireplace("-", "1111111111111111111", $content);
		
		$content = preg_replace("'^[\-]'si", "11111111111111", $content);
	
		// транслит $title
		// $title = iconv("windows-1251", "UTF-8//IGNORE", $title);
		$title_translit = strtolower(strtr($title, $translit));
		$title_translit = preg_replace('%&.+?;%', '', $title_translit);
		$title_translit = preg_replace('%[^a-z0-9,._-]+%', '-', $title_translit);
		$title_translit = trim($title_translit, '-');
		$url2uv_translit=$genre_translit."/".$title_translit;
		$str_url2uv_translit = strval($url2uv_translit);

		// вызов первой ссылки сапы
		$sapeword1 = "<?php include('sape1.php');?>";
		$sapeword2 = "<?php include('sape2.php');?>";
		// поиск перевода строки
		preg_match_all("/\n/", $content, $sapematches, PREG_OFFSET_CAPTURE);
		$sapematches = $sapematches[0];		
		if ( count($sapematches) < 2)
			{
			// тут выдаем ошибку, если 
			echo "<br />$title Отсутствуют последовательности, которые можно сделать сапоссылкой!!!!111";
			$content = $content.$sapeword2;
			continue;
			}
		
		// берем случайное слово и его позицию в тексте для первой сапоссылки
		$half_text = floor((count($sapematches)-1)/2);
		$s1 = rand(0, $half_text);
		$word1 = $sapematches[$s1][0];
		$position1 = $sapematches[$s1][1];
		// разбиваем на куски: до слова, само слово и кусок после слова
		$before_word1 = substr($content, 0, $position1);
		$after_word1 = substr($content, $position1 + strlen($word1));
		// а потом склеиваем обратно
		$content = $before_word1.$sapeword1.$after_word1;
		
		// аналогичено поступаем со вторым выводом сапы, только теперь надо искать перевод строки во сторой половине текста
		preg_match_all("/\n/", $content, $sapematches, PREG_OFFSET_CAPTURE);
		$sapematches = $sapematches[0];		
		$half_text = floor((count($sapematches)-1)/2);
		$s2 = rand($half_text, count($sapematches)-1);
		$word2 = $sapematches[$s2][0];
		$position2 = $sapematches[$s2][1];
		$before_word2 = substr($content, 0, $position2);
		$after_word2 = substr($content, $position2 + strlen($word2));
		$content = $before_word2.$sapeword2.$after_word2;
		
		// делаем произвольное число замен для перелинковки
		$numcrosslinks = rand (1,3);
		for ($icross = 1; $icross <= $numcrosslinks; $icross++)
			{
			// поиск русских слов свыше 7 символов
			preg_match_all("/[А-Яа-я]{7,}+/u", $content, $matches, PREG_OFFSET_CAPTURE);
			$matches = $matches[0];		
			if ( count($matches) == 0)
				{
				// тут выдаем ошибку, если 
				echo "<br />$filename $title Отсутствуют слова, которые можно сделать ссылкой!!!!111";
				continue;
				}
			//берем случайный элемент массива ссылок из первого прохода
			//тут мне зяки помог, с 0+сайзофф  thefuturewillcome
			$rand_url_file = rand(0,0+sizeof($array_url));
			// в переменную $link заносим случайный элемент массива $array_url
			$link = $array_url[$rand_url_file];
			// берем случайное слово и его позицию в тексте
			$r = rand(0, count($matches)-1);
			$word = $matches[$r][0];
			// echo "<br />$word";
			$position = $matches[$r][1];
			// разбиваем на куски: до слова, само слово и кусок после слова
			$before_word = substr($content, 0, $position);
			$after_word = substr($content, $position + strlen($word));
			$word = "<a href=".'~/'."$link>".$matches[$r][0]."</a>";
			// а потом склеиваем обратно
			$content = $before_word.$word.$after_word;
			}

		// переменная $last_1 - это последняя цифра $startfile для первого прохода
		$str_startfile_1=strval($filename);
		$last_1 = $str_startfile_1{strlen($str_startfile_1)-1};
		
		// записана главная страница
		// $last_add_uv = 1
		// записана УВ2-заголовок
		// $last_add_uv = 2
		// записана УВ2-контент
		// $last_add_uv = 3
		// записана УВ3
		// $last_add_uv = 4
		
		// если прошлая страница УВ2, то пишем контент в нее
		if($last_add_uv == 2)
			{
			fwrite($pages,"\n");
			fwrite($pages,"$content\n");
			fwrite($pages,"\n");
			$last_add_uv = 3;
			
			echo "<br /><font color=0000FF>$filename $title контент для УВ2</font>";
			
			continue;
			}
		
		// если последняя цифра $last_1 меньше 7, то пишем УВ3 с ближайшей публикацией
		if(($last_1 < 7))
			{
			$z=0;
			$rndmin = rand (10,59);
			$rndhour = rand (1,11);
			$rndsec = rand (10,59);
			if($rndhour < 10) {$rndhour=$z.$rndhour;}
			$rndday = rand (1, 29);
			if($rndday < 10) {$rndday=$z.$rndday;}
			$rndmonth = rand ($publish_this_year_month_start,$publish_this_year_month_end);
			if($rndmonth < 10) {$rndmonth=$z.$rndmonth;}
			
			// пишем файл
			fwrite($pages,"###$title\n");
			fwrite($pages,$keywords_UV3);
			fwrite($pages,$description_UV3);
			fwrite($pages,"@@publish=$publish_this_year-$rndmonth-$rndday $rndhour:$rndmin:$rndsec\n");
			fwrite($pages,"@@filter=zfilter_php, zfilter_text2html\n");
			fwrite($pages,"\n");
			fwrite($pages,"$content\n");
			fwrite($pages,"\n");
			
			// инкрементируем счетчик страниц, публикуемых в 2010 г.
			$kolvo2010++;
			// инкрементируем счетчик УВ3
			$kolvoUV3++;
			// сейчас пишем - НЕ УВ2!
			$last_add_uv = 4;
			echo "<br /><font color=000000>&nbsp; $filename $title $publish_this_year-$rndmonth-$rndday $rndhour:$rndmin:$rndsec</font>";
			}
		
		// если $last_1 семерка - то пишем УВ4
		if(($last_1 == 7)&&($last_add_uv == 4))
			{
			$z=0;
			$rndmin = rand (10,59);
			$rndhour = rand (1,11);
			$rndsec = rand (10,59);
			if($rndhour < 10) {$rndhour=$z.$rndhour;}
			$rndday = rand (1, 29);
			if($rndday < 10) {$rndday=$z.$rndday;}
			$rndmonth = rand ($publish_this_year_month_start,$publish_this_year_month_end);
			if($rndmonth < 10) {$rndmonth=$z.$rndmonth;}
			
			// пишем файл
			fwrite($pages,"####$title\n");
			fwrite($pages,$keywords_UV3);
			fwrite($pages,$description_UV3);
			fwrite($pages,"@@publish=$publish_this_year-$rndmonth-$rndday $rndhour:$rndmin:$rndsec\n");
			fwrite($pages,"@@filter=zfilter_php, zfilter_text2html\n");
			fwrite($pages,"\n");
			fwrite($pages,"$content\n");
			fwrite($pages,"\n");
			
			// инкрементируем счетчик страниц, публикуемых в 2010 г.
			$kolvo2010++;
			// инкрементируем счетчик УВ4
			$kolvoUV4++;
			// прошлая записанная страница - НЕ УВ2!
			$last_add_uv = 0;
			
			echo "<br /><font color=888888>&nbsp;&nbsp;&nbsp; $filename $title $publish_this_year-$rndmonth-$rndday $rndhour:$rndmin:$rndsec</font>";
			}
			
		// если $last_1 больше восьмерки - то будущей датой (переменная $rndmonth)
		if(($last_1 > 7))
			{
			$z=0;
			$rndmin = rand (10,59);
			$rndhour = rand (1,11);
			$rndsec = rand (10,59);
			if($rndhour < 10) {$rndhour=$z.$rndhour;}
			$rndday = rand (1, 29);
			if($rndday < 10) {$rndday=$z.$rndday;}
			$rndmonth = rand (1,12);
			$rndyear = rand ($publish_this_year,$publish_future_year_end);
			
			// если отложенная публикация попадает в текущий год, то надо уложить месяц начиная с $publish_this_year_month_end и до 12
			if($rndyear == $publish_this_year) {$rndmonth=rand ($publish_this_year_month_end,12);}
			// но месяц все равно надо делать двузначным
			if($rndmonth < 10) {$rndmonth=$z.$rndmonth;}
			
			// инкрементируем счетчик страниц, публикуемых в каждом году
			if ($rndyear == 2010) {$kolvo2010++;}
			if ($rndyear == 2011) {$kolvo2011++;}
			if ($rndyear == 2012) {$kolvo2012++;}
			if ($rndyear == 2013) {$kolvo2013++;}
			if ($rndyear == 2014) {$kolvo2014++;}
			if ($rndyear == 2015) {$kolvo2015++;}
			if ($rndyear == 2016) {$kolvo2016++;}
			if ($rndyear == 2017) {$kolvo2017++;}
			
			// пишем файл
			fwrite($pages,"###$title\n");
			fwrite($pages,$keywords_UV3);
			fwrite($pages,$description_UV3);
			fwrite($pages,"@@publish=$rndyear-$rndmonth-$rndday $rndhour:$rndmin:$rndsec\n");
			fwrite($pages,"@@filter=zfilter_php, zfilter_text2html\n");
			fwrite($pages,"\n");
			fwrite($pages,"$content\n");
			fwrite($pages,"\n");
			
			// инкрементируем счетчик УВ3
			$kolvoUV3++;
			// прошлая записанная страница - НЕ УВ2!
			$last_add_uv = 4;
			
			echo "<br /><font color=FF0000>&nbsp; $filename $title $rndyear-$rndmonth-$rndday $rndhour:$rndmin:$rndsec</font>";
			}
	
		}
	}
	
//*******************************************************************************
//************************ КОНЕЦ ВТОРОГО ПРОХОДА ********************************
//*******************************************************************************

// закрываем файл и каталог
fclose($pages);
closedir($dir);
	
// выводим статистику
$vseUV = $kolvoUV2+$kolvoUV3+$kolvoUV4;
echo "<br /><br />Количество публикаций в 2010г.: ".$kolvo2010;
echo "<br />Количество публикаций в 2011г.: ".$kolvo2011;
echo "<br />Количество публикаций в 2012г.: ".$kolvo2012;
echo "<br />Количество публикаций в 2013г.: ".$kolvo2013;
echo "<br />Количество публикаций в 2014г.: ".$kolvo2014;
echo "<br />Количество публикаций в 2015г.: ".$kolvo2014;
echo "<br />Количество публикаций в 2016г.: ".$kolvo2015;
echo "<br />Количество публикаций в 2017г.: ".$kolvo2015;
echo "<br /><br />Количество УВ2: ".$kolvoUV2;
echo "<br />Количество УВ3: ".$kolvoUV3;
echo "<br />Количество УВ4: ".$kolvoUV4;
echo "<br />Итого страниц: ".$vseUV;
echo "<br /><br />Время работы скрипта: " . round((microtime(true) - $mtime) * 1, 4) . " с.";
?>