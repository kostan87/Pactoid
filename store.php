<?php
include('simple_html_dom.php');

function get_web_page( $url ) {
  $uagent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36 OPR/78.0.4093.147";

  $ch = curl_init( $url );

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
  curl_setopt($ch, CURLOPT_TIMEOUT, 120);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt');
  curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');

  $content = curl_exec( $ch );
  $err     = curl_errno( $ch );
  $errmsg  = curl_error( $ch );
  $header  = curl_getinfo( $ch );
  curl_close( $ch );

  $header['errno']   = $err;
  $header['errmsg']  = $errmsg;
  $header['content'] = $content;
  return $header;
}

$url = 'https://www.citilink.ru/catalog/smartfony/';
$html = new simple_html_dom();
$html->load(get_web_page($url)['content']);
$num = count($html->find('a.ProductCardHorizontal__title  '));
echo get_web_page($url)['errmsg'];

echo "<title>Pactoid</title><main><div class='container'>";
for ($id=1; $id < $num; $id++) {
  $href = $html->find('a.ProductCardHorizontal__title  ')[$id-1]->href;
  $title = explode(', ', substr($html->find('a.ProductCardHorizontal__title  ')[$id-1]->innertext, 17))[0];
  $price = $html->find('span.ProductCardHorizontal__price_current-price ')[$id-1]->innertext;
  $image_src = $html->find('div.ProductCardHorizontal__picture ')[$id-1]->children[0]->{'data-src'};
  print_r("<div class='card'><a href='https://www.citilink.ru" . $href . "'><img src='" . $image_src . "' alt='" . $title . "'><p>" . $title . "</p><span>" . $price . " RUB</span></a></div>");
}
echo "</div></main>";
?>