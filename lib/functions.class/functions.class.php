<?php


if( !defined("DOCROOT" ) ) die("DOCROOT is not defined");

//класс различных функций
class Functions{


      var $user_agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.66 Safari/535.11"; // стандартный USER-AGENT
      var $cookie = "cookie.txt"; // стандартный cookie файл
      var $cookie_path = "cookies/"; // папка к кукисам
      var $run_from = ""; // имя класса из которого запускается этот класс
      var $proxies_path = "proxies/"; // папка с прокси
      var $logs_path = "logs/"; // папка с логамир
      var $logs_num = ""; // номер лога
      var $num_refresh = 0; // количество рекурсий для функции url_open
      var $max_refresh = 10; // максимальное количество рекурсий для функции url_open
      var $config_path = "configs/";
      var $last_point_prefix = "";
      var $decode_page = NULL;
      var $use_proxy = TRUE;
      var $find_page = NULL;
      var $use_cookie = TRUE;
      var $timedout = 10;
      var $proxy_url = "";
      var $tmp_path = "tmp/";
      var $stop_file = "stop_%s.txt";
      var $stop_prefix;
      var $current_ip = "";
      var $logs_prefix = "";


      function __construct(){
      }
      
      
      function installScript( $db_host, $db_user, $db_passwd, $db_name, $tables ){
            global $db_engine;
            if( !$db_host OR !$db_name OR !$db_user OR !$db_passwd OR !$tables ) return;
            if( $db_engine == "mysql" ){
                  mysql_connect($db_host, $db_user, $db_passwd) or die("Could not connect to db");
                  mysql_select_db($db_name) or die("Could not select the database");
                  @mysql_query('SET NAMES utf8;') or die("Error: could not set names");
                  @mysql_query('SET CHARACTER SET utf8;') or die("Error: could not set character");
                  @mysql_query('SET character_set_connection=utf8;') or die("Error: could not set connection");
            }elseif( $db_engine == "mysqli" ){
                  $conn = mysqli_connect($db_host, $db_user, $db_passwd) or die("Could not connect to db");
                  mysqli_select_db( $conn, $db_name ) or die("Could not select the database");
                  @mysqli_query($conn, 'SET NAMES utf8;') or die("Error: could not set names");
                  @mysqli_query($conn, 'SET CHARACTER SET utf8;') or die("Error: could not set character");
                  @mysqli_query($conn, 'SET character_set_connection=utf8;') or die("Error: could not set connection");
            }
            foreach( $tables as $t_name=>$t_data ){
                  $sql = "";
                  $sql .= "CREATE TABLE IF NOT EXISTS `{$t_name}`(\n";
                  for( $i=0; $i < count($t_data['fields']); $i++ ){
                        $field = $t_data['fields'][$i];
                        $sql .= "\t`{$field['name']}` {$field['type']} {$field['additional']}".
                          (((count($t_data['fields']) - $i) == 1 AND isset($t_data['primary_key'])) ? ",\n" : ((count($t_data['fields']) - $i) > 1 ? ",\n" : "\n"));
                  }
                  if( isset($t_data['primary_key']) ){
                        $sql .= "\tPRIMARY KEY(`{$t_data['primary_key']}`)";
                  }
                  $sql .= ") DEFAULT CHARSET=utf8 ENGINE=InnoDB;\n\n";
                  if( $db_engine == "mysql" ){
                        mysql_query( $sql ) or die(mysql_error() ."\nSQL:\n". $sql);
                  }elseif( $db_engine == "mysqli" ){
                        mysqli_query( $conn, $sql ) or die(mysql_error() ."\nSQL:\n". $sql);
                  }
                  $sql = "";
                  if( isset($t_data['data']) ){
                        $sql = "INSERT INTO `{$t_name}` ";
                        $keys = array();
                        foreach( $t_data['data']['keys'] as $k ){
                              $keys[] = "`". mysql_real_escape_string($k) ."`";
                        }
                        $sql .= "(". implode(", ", $keys) .") VALUES\n";
                        for( $i=0; $i < count($t_data['data']['values']); $i++ ){
                              $values_arr = $t_data['data']['values'][$i];
                              $values = array();
                              foreach( $values_arr as $value ){
                                    $values[] = "'". mysql_real_escape_string($value) ."'";
                              }
                              $sql .= "(". implode(", ", $values) .")".
                                ((count($t_data['data']['values']) - $i) == 1 ? "\n" : ",\n");
                        }
                        $sql .= ";";
                        mysql_query( $sql ) or die(mysql_error() ."\nSQL:\n". $sql );
                  }
            }
            return TRUE;
      }
            


      // загрузка нового прокси
      function getProxy($this_proxy=FALSE){
              $file_name = DOCROOT . $this->proxies_path ."/proxy". ($this->run_from ? "_". $this->run_from : "") .".txt";
              if( !file_exists($file_name) ){
                      $parse_proxy = new ParseProxy;
                      $parse_proxy->proxies_path = $this->proxies_path;
                      $parse_proxy->run_from = $this->run_from;
                      if( !$parse_proxy->getHideMe("", $this->run_from) ) die("Ошибка при загрузке прокси");
              }
              $proxys = explode("\n", str_replace("\r", "", @file_get_contents($file_name)));
              if( !$proxys[0] ){
                      $parse_proxy = new ParseProxy;
                      $parse_proxy->proxies_path = $this->proxies_path;
                      $parse_proxy->run_from = $this->run_from;
                      if( !$parse_proxy->getHideMe("", $this->run_from) ) die("Ошибка при загрузке прокси");
                      $proxys = explode("\n", str_replace("\r", "", @file_get_contents($file_name)));
              }
              if( !$this_proxy ){
                      $last_proxy = trim($proxys[0]);
                      $this->setLastProxy( $last_proxy );
                      return trim($proxys[0]);
              }
              $count_proxys = count( $proxys );
              for($i=0; $i<$count_proxys; $i++){
                      if( trim($proxys[$i]) == trim($this_proxy) ){
                              if( $count_proxys-1 < $i+1 ){
                                    $this->setLastProxy( $proxys[0] );
                                    return trim($proxys[0]);
                              }
                              $last_proxy = trim($proxys[$i+1]);
                              $this->setLastProxy( $last_proxy );
                              return $last_proxy;
                      }
              }
              $this->setLastProxy( trim($proxys[0]) );
              return trim($proxys[0]);
      }


      // устанавливаем последний прокси
      function setLastProxy( $proxy ){
              $file_name = DOCROOT . $this->proxies_path ."last_proxy". ($this->run_from ? "_". $this->run_from : "") .".txt";
              if( !$proxy ){
                      return FALSE;
              }
              if( file_put_contents($file_name, $proxy) ){
                      return TRUE;
              }
              return FALSE;
      }


      // загружаем последний прокси
      function getLastProxy(){
              $file_name = DOCROOT . $this->proxies_path ."last_proxy". ($this->run_from ? "_". $this->run_from : "") .".txt";
              if( !file_exists( $file_name ) ){
                      return $this->getNewProxy();
              }
              $proxy = file_get_contents($file_name);
              if( !$proxy ){
                      return $this->getNewProxy();
              }
              return $proxy;
      }
              

      // загружем новый прокси
      function getNewProxy(){
              $last_proxy = $this->getLastProxy();
              return $this->getProxy( $last_proxy );
      }


      // удаляем из списка нерабочий прокси
      function dropProxy( $proxy ){
              if( !$proxy ) return;
              $file_name = DOCROOT . $this->proxies_path ."proxy". ($this->run_from ? "_". $this->run_from : "") .".txt";
              $proxys = explode("\n", file_get_contents($file_name));
              $proxys1 = array();
              foreach( $proxys as $val ){
                      if( strpos($val, $proxy) === FALSE ){
                              $proxys1[] = $val;
                      }
              }
              file_put_contents( $file_name, implode("\n", $proxys1) );
      }


      // удаляет двойные пробелы в строке
      function delTwoSpaces( $string ){
            $string = trim( $string );
            while( TRUE ){
                  if( strpos($string, "  ") === FALSE ) break;
                  $string = str_replace( "  ", " ", $string );
            }
            return $string;
      }


      // удаляет пустые значениея из массива
      function delNullRows( $array ){
            $array1 = array();
            foreach( $array as $row ){
                  if( $row ) $array1[] = $row;
            }
            return $array1;
      }


      // функция логирования
      /**
        @param $mess - строка
        @param $params - массив
        @param $params['prefix'] - префикс имени лога
        @param $params['date'] - писать ли дату перед сообщением
        @param $params['br'] - ставить ли перенос строки в конце сообщения
      */
      function logs($mess, $params=array("prefix" => "", "date" => TRUE, "br" => TRUE) ){
            if( !@$params['prefix'] ) $params['prefix'] = $this->logs_prefix;
            $f_name = DOCROOT . $this->logs_path ."logs". (@$params['prefix'] ? "_". $params['prefix'] : "") . ($this->logs_num ? "_". $this->logs_num : "") .".html";
            if( !is_file($f_name) ) $this->flushLogs( @$params['prefix'] );
            $f = fopen( $f_name, "a+");
            if( !$f ){
                  die("Сайт на реконтсрукции");
            }
            if( !isset($params['date']) ) $params['date'] = TRUE;
            if( !isset($params['br']) ) $params['br'] = TRUE;
            $mess = @$params['date'] ? @date("H:i:s") ."  ". $mess : $mess;
            fwrite($f, $mess . (@$params['br'] ? "<br>\n" : "")) or die("error");
            fclose($f);
            flush();
      }


      // функция очистки лога
      function flushLogs( $prefix="" ){
            if( !$prefix ) $prefix = $this->logs_prefix;
            file_put_contents( DOCROOT . $this->logs_path ."logs". ($prefix ? "_". $prefix : "") . ($this->logs_num ? "_". $this->logs_num : "") .".html",
                "<!DOCTYPE html><head><meta charset=\"utf-8\"></head><body>" );
      }


      // функция разжатия страницы gzip
      // взята где-то с интернета
      public static function ungzip_file($source, $dest=''){
          if ($dest == '')
              $dest = preg_replace('/.gz$/i', '', $source);

          $error = false;

          if($fp_in = @gzopen($source, 'rb'))
          {
              if($fp_out = fopen($dest, 'wb'))
              {
                  while(!feof($fp_in))
                      @fwrite($fp_out, @gzread($fp_in,1024*512));
                  @gzclose($fp_in);
              }
              else
                  $error = true;

              fclose($fp_out);
          }
          else
              $error = true;

          if($error)
                return false;
          else
              return filesize($dest);
      }
      
      
      function writeCookieStr( $header, $cookie_name ){
            preg_match_all("/Set-Cookie: (.*?)=(.*?);/i",$header,$res);

            $cookie='';
            $cookies = explode(";", $this->getCookieStr($cookie_name));
            for( $i=0; $i < count($cookies); $i++ ){
                if( !$cookies[$i] ) continue;
                $c = explode("=", $cookies[$i]);
                $k = array_shift($c);
                $c = implode( "=", $c );
                $cookies[$k] = $c;
                unset($cookies[$i]);
            }
            
            foreach ($res[1] as $key => $value) {
                if( strpos($res[2][$key], "deleteMe") !== FALSE OR strpos($res[2][$key], "delete me") !== FALSE ){
                    unset($cookies[$value]);
                    continue;
                }
                $cookies[$value] = $res[2][$key];
            }
            $cookie = array();
            foreach( $cookies as $k=>$v ){
                if( !$k ) continue;
                $cookie[] = $k ."=". $v;
            }
            file_put_contents( DOCROOT . $this->cookie_path . $cookie_name ."_c.txt", implode(";", $cookie) );
      }
      
      
      function getCookieStr( $cookie_name ){
            return @file_get_contents( DOCROOT . $this->cookie_path . $cookie_name ."_c.txt" );
      }


      // функция для определения имени хоста в хидере Location
      function getLocationHost( $headers ){
            if( !$headers ) return "";
            if( stripos($headers, "\r\nLocation") !== FALSE ){
                  preg_match("#(?i)Location:(.+)\s{0,2}#", $headers, $location);
                  if( $location ){
                        $location[1] = str_replace( array(" ", "https://"), array("", "http://"), $location[1] );
                        $host = @parse_url( $location[1] );
//                         var_dump( $host );
                        if( @$host['host'] ){
                              return $host['host'];
                        }
                  }
             }
             return "";
      }


      // определяет является ли правильной страница, котрая получена через прокси
      function isRightPage( $result ){
            if( !$this->use_proxy ) return FALSE;
            if( !$result ) return 35;
            global $this_proxy;
            if( !$this_proxy ) return FALSE;
            $finds = array( "Извините, мы получили слишком много запросов от вашего IP адреса.",
                "504 Gateway Time-out", 
                "Access Denied",
                "Network Error (dns_server_failure)",
                "The page cannot be displayed",
                "503 Service Temporarily Unavailable",
                "<title>404 Not Found</title>",
                "<SPAN class=TitleDescription> Не удается отобразить страницу</SPAN>",
                "javascript:location.replace('/c')",
                "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=gb2312\">",
                "<big>Network Error (gateway_error)</big>",
                "<h1>404 | Not Found | sfError404Exception</h1>",
                "502 Proxy Error。The ISA Server",
                "The requested URL was not found on this server",
                "<h1>HTTP Status 404",
                "<title>Error 404: Not Found</title>",
                "<title>Welcome To Zscaler Directory Authentication</title>",
                "Location: https://gateway.zscaler",
                "403 Forbidden",
                "400 Bad Request",
                "<TITLE>Mensaje de error</TITLE>",
                "The requested URL could not be retrieved",
                "http://www.goracatrzydziestka.co.uk/",
                "<title>502 Bad Gateway",
                "connect to host",
                "Connection timed ou",
                "ERROR: Gateway Timeout",
                "500 Internal Server Error",
                "<h1>403: доступ запрещен</h1>",
                "<TITLE>ОШИБКА: Запрошенный URL не может быть доставлен</TITLE>",
                "<title>999: request failed</title>",
                "<h1>Request denied</h1>",
                "Access control configuration prevents your request from being allowed at this time",
                "<h2>Cache Доступ запрещён.</h2>",
                "The requested URL could not be retrieved",
            );
            $i = 1;
            foreach( $finds as $r ){
                  if( stripos($result, $r) !== FALSE ){
                        if( $r == "<h1>Request denied</h1>" ) unlink(DOCROOT . $this->cookie_path . $params['cookie'] .".txt");
                        return $i;
                  }
                  $i += 1;
            }
            return FALSE;
      }


      function searchOnPage( $page ){
            if( !$this->find_page ) return FALSE;
            if( is_string($this->find_page) ){
                  if( stripos($page, $this->find_page) === FALSE ) return FALSE;
                  return TRUE;
            }elseif( is_array($this->find_page) ) {
                  foreach( $this->find_page as $item ){
//                         var_dump( $item );
                        if( stripos($page, $item) !== FALSE ) return TRUE;
                  }
                  return FALSE;
            }
            return FALSE;
      }
      
      
      function encodePageToUtf8( $page ){
            preg_match( '#(?i)<meta.+?charset=(?:\'|")(.[^\'"]+?)\'#', $page, $val );
            if( !$val ){
                  preg_match( '#(?i)<meta.+?content=(?:\'|")text/html; charset=(.[^\'"]+?)(?:\'|")#', $page, $val );
            }
            if( !$val ){
                  preg_match( '#Content-Type: .{1,30}; charset=(.+?)(?:"|\')#', $page, $val );
            }
            if( !$val ) return $page;
//             var_dump( $val, $page );
            $charset = strtolower( $val[1] );
            if( $charset == "utf-8" OR $charset == "utf8" ) return $page;
            $p = iconv($charset, "UTF-8", $page);
            if( $p ) return $p;
            return $page;
      }
      
      
      function isStop(){
            if( !$this->stop_prefix ) return;
            $fn = DOCROOT . $this->tmp_path . str_replace("%s", $this->stop_prefix, $this->stop_file);
            if( is_file($fn) ){
                unlink( $fn );
                die;
            }
      }
      
      
      function myurlencode( $url ){
            $url1 = parse_url( $url );
            $query = @$url1['query'];
            if( !$query ) return $url;
            $query = explode( "&", $query );
            foreach( $query as $k=>$v ){
                $v = explode( "=", $v );
                $v[0] = urlencode(urldecode($v[0]));
                $v[1] = urlencode(urldecode(@$v[1]));
                $query[$k] = implode( "=", $v );
            }
            $url1['query'] = implode("&", $query);
            return $url1['scheme'] . '://' . $url1['host'] . @$url1['path'] . ( @$url1['query'] ? '?'. $url1['query'] :'' );
      }     


      function url_open( $url, $params=array("referer" => NULL, "post_data" => NULL, "header" => NULL, "header_req" => NULL, "file" => NULL,
         "cookie" => NULL, "info" => FALSE, "is_photo" => FALSE, "cookie_str" => "", "user_agent" => "", "current_ip" => "", "ssl_version" => NULL) ){
            global $this_proxy;
            $url = str_replace("&amp;", "&", $url );
            if( $this->max_refresh AND $this->num_refresh >= $this->max_refresh ){
                  $this->num_refresh = 0;
                  var_dump( $url, $params );
                  return FALSE;
            }
            $url = $this->myurlencode( $url );
            $this->isStop();
            
            if( !isset($params['timedout']) OR !@$params['timedout'] ) $params['timedout'] = $this->timedout + 30;
            if( !isset($params['current_ip']) OR !@$params['current_ip'] ) $params['current_ip'] = $this->current_ip;
            if( !@$params['user_agent'] ){
//                 $params['user_agent'] = $this->user_agent;
            }
//             var_dump( $this->current_ip, $params ) ;die;
//             var_dump( $this->timedout, $this->find_page );
            $this->num_refresh += 1;
            if( !$url ){
                  $this->num_refresh = 0;
                  $this->logs( "URL must be a string" );
                  return FALSE;
            }
//             var_dump( $url, $params );
            
            
            if( !@$params['cookie'] ) $params['cookie'] = $this->cookie;
            $headers = array("Connection: keep-alive", "Accept-Encoding: identity", "Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/webp, image/jpeg, image/gif, image/x-xbitmap, */*;q=0.1", "Accept-Language: uk-UA,uk;q=0.9,en;q=0.8", "Cache-Control: no-cache");
            if( $this->proxy_url ){
                $headers[] = "Cookie-Name: ". @$params['cookie'];
            }
            
//             $ch = curl_init();

            if( $this->proxy_url ){
                $ch = curl_init( $this->proxy_url . urlencode($url) );
//                 curl_setopt( $ch, CURLOPT_URL, $this->proxy_url . urlencode($url) );
            }else{
                $ch = curl_init( $url );
//                 curl_setopt( $ch, CURLOPT_URL, $url );
            }
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            
//             curl_setopt($ch, CURLOPT_VERBOSE, TRUE );
            if( @$params['ssl_version'] ){
//                 curl_setopt($ch, CURLOPT_SSLVERSION, intval($params['ssl_version']));
            }
//             var_dump( $params['current_ip'] );
            if( $params['current_ip'] && strpos(@$params['current_ip'], "http") === FALSE ){
                curl_setopt($ch, CURLOPT_INTERFACE, preg_replace('#[\s]+?#', "", $params['current_ip']) ."\r");
                if( !preg_match('#\d+\.\d+\.\d+\.\d+#', $params['current_ip']) ){
                    curl_setopt($ch, CURL_VERSION_IPV6, TRUE);
                }
//                 curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                if( @$params['use_ipresolve'] ){
//                     echo "ololo";
                }
                $params['current_ip'] = preg_replace('#[\s]+?#', "", $params['current_ip']) ."\r";
//                 var_dump( $params['current_ip'] );
            }
            if( @$params['file'] ){
                  curl_setopt($ch, CURLOPT_FILE, @$params['file']);
            }
            if( @$params['head_req'] ){
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "HEAD");
            }
            if( !@$params['file'] ){
                  curl_setopt($ch, CURLOPT_HEADER, 1);
            }
            if( @$params['cookie_str'] ){
                  curl_setopt( $ch, CURLOPT_COOKIE, @$params['cookie_str'] );
            }
            if( @$params['xmlrequest'] ){
                  $headers[] = "X-Requested-With: XMLHttpRequest";
            }
            if( is_array(@$params['add_headers']) ){
                  $headers = array_merge( $headers, $params['add_headers'] );
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            //user agent
            if( @$params['user_agent'] ){
                  curl_setopt($ch, CURLOPT_USERAGENT, $params['user_agent']);
            }elseif( !isset($_SERVER['HTTP_USER_AGENT']) AND !@$_SERVER['HTTP_USER_AGENT'] ){
                  curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
            }else{
                  curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            }

            //wait for reply timeout
            curl_setopt($ch, CURLOPT_TIMEOUT, $params['timedout']);

            //verify SSL certificate
//             if( @$params['ssl_version'] ){
//             curl_setopt($ch, CURLOPT_SSLVERSION, 4);
//             curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
//             }
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            if( $params['cookie'] AND $this->use_cookie AND !@$params['cookie_str'] AND !$this->proxy_url ){
                  //get cookie
                  curl_setopt($ch, CURLOPT_COOKIEFILE, DOCROOT . $this->cookie_path . $params['cookie'] .".txt");
                  //write cookie
                  curl_setopt($ch, CURLOPT_COOKIEJAR, DOCROOT . $this->cookie_path . $params['cookie'] .".txt");
            }

            curl_setopt($ch, CURLINFO_HEADER_OUT, true);

            //referer
            if( @$params['referer'] ){
                curl_setopt($ch, CURLOPT_REFERER, $params['referer']);
            }
            //set post data
            if( @$params['post_data'] ){
                curl_setopt($ch, CURLOPT_POST, true);
                if( is_array($params['post_data']) ){
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $params['post_data']);
                }else{
                      curl_setopt($ch, CURLOPT_POSTFIELDS, "{$params['post_data']}");
                }
            }
            
            
//             curl_setopt( $ch, CURLOPT_FRESH_CONNECT, 1 );

            //if is proxy
            if( $this_proxy AND $this->use_proxy ){
//                   var_dump( "proxy" );die;
//                   curl_setopt( $ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );
                  if( strpos($url, "https://") !== FALSE ) curl_setopt( $ch, CURLOPT_HTTPPROXYTUNNEL, 1 );
                  curl_setopt($ch, CURLOPT_PROXY, $this_proxy);
            }
            
            $result = curl_exec($ch);
//             var_dump(curl_getinfo($ch));echo "\n\n";
//             var_dump( $params, $result );die;
            if( $this->current_ip && !preg_match('#\d+\.\d+\.\d+\.\d+#', $this->current_ip) AND !$result ){
//                 $params['use_ipresolve'] = TRUE;
                $errno = curl_errno($ch);
                if( $errno == 4555 ){
                    $params['ssl_version'] = 4;
//                     $params['use_ipresolve'] = TRUE;
                    return $this->url_open( $url, $params );
                }elseif( $errno == 7777 ){
                    var_dump( "777777" );
                    $params['ssl_version'] = 2;
                    return $this->url_open( $url, $params );
                }
                var_dump( curl_errno($ch), curl_error($ch), $params, $result );die;
                return $this->url_open( $url, $params );
            }
            if( !$this->use_proxy AND !$result AND $this->proxy_url ){
                curl_close($ch);
                sleep(5);
                return $this->url_open( $url, $params );
            }
            $result = $this->encodePageToUtf8( $result );
//             var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));echo "\n\n";
//             var_dump( $result );
            if( stripos($result, "<head><title>Document Moved</title></head>") !== FALSE AND $file ){
                    curl_close( $ch );
                    echo "asdasdsd";
                    preg_match( "#(?i)<a HREF=\"(.+?)\">#", $result, $href );
                    $params['post_data'] = NULL;
//                     var_dump( $params );
                    return $this->url_open( $href[1], $params );
            }
            if( @$params['file'] ){
                  $this->num_refresh = 0;
                  curl_close( $ch );
                  return;
            }
            

//             $this->logs( "OUT:<br>\n". curl_getinfo($ch, CURLINFO_HEADER_OUT), array("prefix" => "headers") );
            
            $host = @parse_url($url);
            $host = @$host['host'];
            $host_location = $this->getLocationHost( $result );
//             var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));echo "\n\n";
//             var_dump( $this_proxy );
//             var_dump( $this->isRightPage($result) );

            if( $this->use_proxy AND $this_proxy AND !@$params['is_photo']
                AND !$this->searchOnPage($result) AND stripos($result, "Location:") === FALSE ){
//                     var_dump( "DELETE FILE: ", unlink( DOCROOT . $this->cookie_path . $params['cookie'] .".txt" ) );
                    sleep( 1 );
                    $err = curl_error($ch);
                    if( (strpos($err, "Operation timed out after") !== FALSE OR strpos($err, "Empty reply from server") !== FALSE) AND $this->num_refresh > 0 ){
                          $this->num_refresh -= 1;
                    }
                    if( !$err ){
//                           var_dump( $result );die;
                    }
                    if( strpos($err, "connect() timed out") !== FALSE AND strpos($url, "https://") !== FALSE ){
//                           var_dump( curl_errno($ch), $err, $url, $this_proxy );
//                           die;
                    }
                    if( $err == "Empty reply from server" ){
                          $this->num_refresh -= 1;
                          $this->logs( "RESULT: ". $result );
                    }
                    $this->logs( "Плохой прокси {$this_proxy}. CURL_ERROR37: {$err} on URL {$url}" );
//                     var_dump( $result );
                    curl_close( $ch );
//                     var_dump( $result );
                    $this_proxy1 = $this_proxy;
                    $this_proxy = $this->getProxy($this_proxy);
                    $this->dropProxy($this_proxy1);
                    $reply = $this->url_open( $url, $params );
                    return $reply;
              }elseif( $this->use_proxy AND $this_proxy AND stripos($result, "Location") !== FALSE AND $host_location AND $host AND stripos($host_location, $host) === FALSE AND stripos($result, "pass.pronto.ru") === FALSE ){
                    sleep( 1 );
                    $err = curl_error($ch);
                    $this->logs( "Плохой прокси {$this_proxy}. CURL_ERROR38: {$err}" );
                    curl_close( $ch );
//                     var_dump( $result );
                    $this_proxy1 = $this_proxy;
                    $this_proxy = $this->getProxy($this_proxy);
                    $this->dropProxy($this_proxy1);
                    $reply = $this->url_open( $url, $params );
                    return $reply;
              }elseif( is_int($result_check = $this->isRightPage($result)) ){
                    sleep( 1 );
                    $err = curl_error($ch);
                    $this->logs( "Плохой прокси {$this_proxy}. CURL_ERROR{$result_check}: {$err}" );
                    curl_close( $ch );
                    $this_proxy1 = $this_proxy;
                    $this_proxy = $this->getProxy($this_proxy);
                    $this->dropProxy($this_proxy1);
                    $reply = $this->url_open( $url, $params );
                    return $reply;
              }
//               var_dump( $result );

              $result = explode("\r\n\r\n", $result);
              $header = array_shift( $result );
              if( count(explode("\r\n", $header)) < 3 OR stripos($header, "HTTP/1.0 200 Connection Established") !== FALSE ){
                    $header = array_shift( $result );
              }
//               var_dump(curl_getinfo($ch, CURLINFO_HEADER_OUT));echo "\n\n";
              $result = implode("\r\n\r\n", $result);
//               var_dump( $header );
              if( stripos($header, "100 continue") !== FALSE ){
                    $result = explode("\r\n\r\n", $result);
                    $header = array_shift( $result );
                    $result = implode("\r\n\r\n", $result);
              }
              
              if( strpos($result, "<h1>Request denied</h1>") !== FALSE ){
                    echo "Request denied with IP ". $params['current_ip'] ."<br>\n";
                    var_dump( $result );
                    die;
              }
//               if( $this->proxy_url ){
//                     $this->writeCookieStr( $header, @$params['cookie'] );
//               }
//               $this->logs( "IN:<br>\n". $header, array("prefix" => "headers") );
              if( $this->use_proxy AND stripos($header, "\nLocation:") === FALSE AND !$result ){
//                     var_dump( "DELETE FILE: ", unlink( DOCROOT . $this->cookie_path . $params['cookie'] .".txt" ) );
//                     sleep( 1 );
                    $err = curl_error($ch);
                    $this->logs( "Плохой прокси {$this_proxy}. CURL_ERROR40: {$err}" );
                    curl_close( $ch );
                    $this_proxy1 = $this_proxy;
                    $this_proxy = $this->getProxy($this_proxy);
                    $this->dropProxy($this_proxy1);
                    $reply = $this->url_open( $url, $params );
                    return $reply;
              }
              if( stripos($header, "\nLocation:") !== FALSE ){
                    preg_match("#(?i)Location:(.+)\s{0,2}#", $header, $location);
//                     var_dump( $location );
                    $location = trim( str_replace(array("\n", "\r"), "", $location[1] ) );
                    $last_url = parse_url($url); 
//                     var_dump( $last_url, $url );
                    $url2 = parse_url($location);
                    if (!@$url2['scheme']) $url2['scheme'] = $last_url['scheme'];
                    if (!@$url2['host']) $url2['host'] = $last_url['host'];
                    if (!@$url2['path']) $url2['path'] = @$last_url['path'];
                    if( substr($url2['path'], 0, 1) != "/" ) $url2['path'] = "/". $url2['path'];
                    $location = $url2['scheme'] . '://' . $url2['host'] . $url2['path'] . ( @$url2['query'] ? '?'. $url2['query'] :'' );
                    curl_close( $ch );
                    $params['post_data'] = NULL;
                    return $this->url_open( $location, $params );
              }
              if( is_array($this->decode_page) ){
                    $result2 = @iconv( $this->decode_page[0], $this->decode_page[1], $result );
                    if( $result2 ){
                          $result = $result2;
                          unset( $result2 );
                    }
              }
              if( @$params['info'] ){
                    $result = array($result, curl_getinfo($ch));
              }
              if( @$params['filename'] ){
                    preg_match( "#filename=(.+?)\r#", $header, $val );
                    $val = $val ? str_replace( array("'", '"'), "", $val[1] ) : "";
                    if( is_array($result) ){
                          $result[] = $val;
                    }else{
                          $result = array($result, $val );
                    }
              }
              
              $this->num_refresh = 0;
//               var_dump( $result );
              return $result;
      }


      function urlencoder( $array, $type=1, $func=0 ){
              $posts =array();
              $func = !$func ? "urlencode" : "rawurlencode";
              if( $type == 1 ){
                    foreach( $array as $val ){
                          $posts[] = $val[0] ."=". $func($val[1]);
                    }
              }else{
                    foreach( $array as $k=>$v ){
                          $posts[] = $k ."=". $func( $v );
                    }
              }
              return implode("&", $posts);
      }


      function translitIt($str) 
      {
            $tr = array(
                    "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
                    "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
                    "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
                    "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
                    "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
                    "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
                    "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
                    "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
                    "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
                    "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
                    "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
                    "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
                    "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", " " => "_", "&amp;" => "", "&" => ""
            );
            return strtr($str,$tr);
      }


      // функция загрузки конфига
      function getConfig( $config_name ){
            if( !$config_name ) return FALSE;
            if( !is_file(DOCROOT . $this->config_path . $config_name .".cfg") ) return array();
            $file = explode( "\n", file_get_contents(DOCROOT . $this->config_path . $config_name .".cfg") );
            $config = array();
            foreach( $file as $row ){
                  if( !$row ) continue;
                  $row = explode( "=", $row );
                  $config[ trim(array_shift($row)) ] = trim( implode("=", $row) );
            }
            return $config;
      }


      // функция сохранения конфига
      function saveConfig( $config_name, $configs ){
            if( !$config_name OR !$configs ) return;
            $f = fopen( DOCROOT . $this->config_path . $config_name .".cfg", "w" );
            foreach( $configs as $key=>$val ){
                  fwrite($f, $key ." = ". $val ."\n");
            }
            fclose($f);
      }


      // обрезка изображения
      function resizeImage($src, $out, $width, $height, $color = 0xFFFFFF, $quality = 100){
          // Если файл не существует
          if (!file_exists($src)) return;
          $size = getimagesize($src);
            if( !$size ) return;
          $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
          if($format==''){
                $ext=@split("\.", $src);
                $ext=array_splice($ext, -1);
                $format=strtolower($ext[0]);
          }
            if( $format == "jpg" ) $format = "jpeg";
            if( !in_array($format, array("jpeg", "png", "gif")) ) return;
          $picfunc = 'imagecreatefrom'.$format;
          $gor = $width  / $size[0];
            if( !$gor ) return;
          // Вертикальное соотношение
          $ver = $height / $size[1];
            if( !$ver ) return;
          // Если не задана высота, вычислить изходя из ширины, пропорционально
          if ($height == 0) {
              $ver = $gor;
              $height  = $ver * $size[1];
          }
          // Так же если не задана ширина
          elseif ($width == 0) {
              $gor = $ver;
              $width   = $gor * $size[0];}
          // Формируем размер изображения
          $ratio   = min($gor, $ver);
          // Нужно ли пропорциональное преобразование
          if ($gor == $ratio){
              $use_gor = true;
          }
          else{
              $use_gor = false;
          }
          $new_width   = $use_gor  ? $width  : floor($size[0] * $ratio);
          $new_height  = !$use_gor ? $height : floor($size[1] * $ratio);
          $new_left    = $use_gor  ? 0 : floor(($width - $new_width)   / 2);
          $new_top     = !$use_gor ? 0 : floor(($height - $new_height) / 2);
          $picsrc  = @$picfunc($src);
          if( !$picsrc ){
                imagedestroy( $picsrc );
                return;
          }
          // Создание изображения в памяти
          $picout = imagecreatetruecolor($width, $height);
          // Заполнение цветом
          imagefill($picout, 0, 0, $color);
          // Нанесение старого на новое
          imagecopyresampled($picout, $picsrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
          // Создание файла изображения
          imagejpeg($picout, $out, $quality);
          // Очистка памяти
          imagedestroy($picsrc);
          imagedestroy($picout);
          return true;
      }


      // функция сохранения последней точки
      // используется на серверах где время выполнения скрипта ограничено
      function saveLastPoint( $array, $prefix="" ){
            $prefix = $this->last_point_prefix ? $this->last_point_prefix : $prefix;
            file_put_contents( DOCROOT . $this->config_path . "last_point". ($prefix ? "_". $prefix : "") .".cfg", serialize($array) );
      }


      // функция загрузки последней точки
      function getLastPoint( $prefix="" ){
            $prefix = $this->last_point_prefix ? $this->last_point_prefix : $prefix;
            if( !is_file(DOCROOT . $this->config_path . "last_point". ($prefix ? "_". $prefix : "") .".cfg") ) return array();
            $point = file_get_contents( DOCROOT . $this->config_path . "last_point". ($prefix ? "_". $prefix : "") .".cfg" );
            $point = unserialize( $point );
            return $point;
      }


      function cropImage($file_input, $file_output, $crop = 'square',$percent = false) {
            list($w_i, $h_i, $type) = getimagesize($file_input);
            if (!$w_i || !$h_i) {
                  echo 'Невозможно получить длину и ширину изображения';
                  return;
            }
            $types = array('','gif','jpeg','png');
            $ext = $types[$type];
            if ($ext) {
                  $func = 'imagecreatefrom'.$ext;
                  $img = $func($file_input);
            } else {
                  echo 'Некорректный формат файла';
                  return;
            }
            if ($crop == 'square') {
                  $min = $w_i;
                  if ($w_i > $h_i) $min = $h_i;
                  $w_o = $h_o = $min;
            } else {
                  list($x_o, $y_o, $w_o, $h_o) = $crop;
                  if ($percent) {
                          $w_o *= $w_i / 100;
                          $h_o *= $h_i / 100;
                          $x_o *= $w_i / 100;
                          $y_o *= $h_i / 100;
                  }
                  if ($w_o < 0) $w_o += $w_i;
                  $w_o -= $x_o;
                  if ($h_o < 0) $h_o += $h_i;
                  $h_o -= $y_o;
            }
//             var_dump( $w_o, $h_o );
            $img_o = imagecreatetruecolor($w_o, $h_o);
            imagecopy($img_o, $img, 0, 0, $x_o, $y_o, $w_o, $h_o);
            if ($type == 2) {
                  return imagejpeg($img_o,$file_output,100);
            } else {
                  $func = 'image'.$ext;
                  return $func($img_o,$file_output);
            }
      }


      function decode( $string, $from="windows-1251", $to="UTF-8" ){
            if( !$string ) return $string;
            $buf = @iconv($from, $to, $string );
            if( !$buf ) return $string;
            return $buf;
      }

}




// класс парсинга прокси
class ParseProxy{


      var $run_from = "";
      var $proxies_path = "proxies/";


      function __construct(){
              $this->funcs = new Functions;
              $config = $this->funcs->getConfig( "general" );
      }


      function isChinaProxy( $proxy ){
            $prefixes = array( "123.125.", "219.234.", "180.96.", "58.252.", "116.255.", "118.195." );
            foreach( $prefixes as $prefix ){
                  if( strpos($proxy, $prefix) !== FALSE ) return TRUE;
            }
      }


      function getHideMe( $code="", $run_from="" ){
          global $this_proxy;
          if( !$run_from ) $run_from = $this->run_from;
          $this_proxy = NULL;
          $file_name = DOCROOT . $this->proxies_path ."proxy". ($run_from ? "_". $run_from : "") .".txt";
          if( !$code ){
              $this->config = $this->funcs->getConfig( "general" );
              $code = $this->config['code_proxy'];
          }
          $this->funcs->logs( "http://hideme.ru/api/proxylist.txt?country=RUUA&type=s45&anon=4&out=plain&code={$code}" );
          $list_proxy = iconv("cp1251", "utf-8", file_get_contents( "http://hideme.ru/api/proxylist.txt?country=RUUA&type=s45&anon=4&out=plain&code={$code}" ));
          if( stripos($list_proxy, "Для доступа") !== FALSE ) return FALSE;
          $proxies = array();
          foreach( explode("\n", $list_proxy) as $proxy ){
                if( $this->isChinaProxy($proxy) ) continue;
                $proxies[] = str_replace( "\r", "", $proxy );
          }
          file_put_contents( $file_name, implode("\n", $proxies) );
          return TRUE;
      }


}


?>