<?php
  require_once("/Users/sh/myapps/basic_php_v3-master/work/phpQuery-onefile.php");
  require '/Users/sh/myapps/basic_php_v3-master/work/vendor/autoload.php';

  use JonnyW\PhantomJs\Client;
  use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

  $client = Client::getInstance();
  $request = $client->getMessageFactory()->createRequest();
  $response = $client->getMessageFactory()->createResponse();

  $url = 'https://www3.nhk.or.jp/news/catnew.html';
  $request->setUrl($url);
  $client->send($request,$response);

  $htmlstr = $response->getContent();
  $dom = new DOMDocument;
  @$dom->loadHTML($htmlstr);
  $dom = $dom->saveHTML();
  $doc = phpQuery::newDocument($dom);
  
  $fp = fopen('data.html', 'w');
  fwrite($fp, $doc);
  fclose($fp);
  
  $html = file_get_contents("data.html");
  $doc = phpQuery::newDocument($html);

  $file = fopen("news.html", "w");
  for($i=0;$i<20;$i++){
    $title[$i] = $doc->find("body #content .content--list li:eq($i) dl dd a em")->text();
    $time[$i] = $doc->find("body #content .content--list li:eq($i) dl dd a time")->text();
    $tag[$i] = $doc->find("body #content .content--list li:eq($i) dl dd span .i-word")->text();
    $link [$i]= "https://www3.nhk.or.jp/".$doc->find("body #content .content--list li:eq($i) dl dd a ")->attr("href");
    fputs($file, $title[$i].$time[$i].$tag[$i].$link [$i]."\n");
  }
  fclose($file);

  $html = file_get_contents("news.html");  
  $dom= phpQuery::newDocument($html);
  $news = $dom->text();

  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  if(mb_send_mail("xxbbxx825@gmail.com", "最新ニュース", $news))
  {
    echo "メール送信成功です";
  }
  else
  {
    echo "メール送信失敗です";
  }
?>
