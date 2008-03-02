<?php

/**
 * xbkUriParser анализирует, разбирает и собирает URL
 *
 * @version    1.0   2007-12-13
 */

class xbkUriParser
{

    /*
    parse - анализирует и разбирает ссылку.
    ¬озвращает ассоциативный массив, содержащий следующие ключи:
        'type' - тип ссылки (abs - абсолютна€, root - относительна€ от корн€, rel - относительна€, fragment - только фрагмент);
        'scheme' - протокол соединени€;
        'host' - им€ хоста;
        'port' - порт;
        'user' - им€ пользовател€;
        'pass' - пароль;
        'path' - внутренний путь;
        'query' - строка параметров;
        'fragment' - сноска;
        'path_dir' - часть внутреннего пути в виде структуры каталогов;
        'path_file' - часть внутреннего пути в виде конечного файла.
    */
    public function parse($url = null)
    {
        global $_SERVER;
        if ($url === null) $url = $_SERVER['REQUEST_URI']; // –азбирает текущую ссылку, если ничего не задано
        $return_array = Array();
        $scheme_array = Array('http', 'https', 'ftp');
        $del = '`';
        $scheme_subpattern = '('.implode('|', $scheme_array).')';
        $abs = true; // абсолютный адрес
        $pattern = $del.'^'
        .$scheme_subpattern
        .preg_quote('://', $del)
        ."([a-zA-Z0-9\-.:@]{1,})"
        ."(.*)"
        .$del."i";
        $result = preg_match_all($pattern, $url, $matches);
        $other = '';
        $other = $url;
        if (isset($matches[0][0])) {
            $return_array['type'] = 'abs'; // abs , root , rel , fragment
            $return_array['scheme'] = $matches[1][0];
            $addr = $matches[2][0];
            $other = $matches[3][0];
            $pattern = $del.'^'
            ."([a-zA-Z0-9\-.]{0,})"
            .preg_quote(":", $del)
            ."([a-zA-Z0-9\-.]{0,})"
            .preg_quote("@", $del)
            ."([a-zA-Z0-9\-.]{1,})"
            ."$"
            .$del."i";
            $result = preg_match_all($pattern, $addr, $matches);
            if (isset($matches[0][0])) {
                $return_array['user'] = $matches[1][0];
                $return_array['pass'] = $matches[2][0];
                $return_array['host'] = $matches[3][0];
            } else $return_array['host'] = $addr;
        } else {
            $abs = false;
            $other = $url;
        }
        // ¬сЄ остальное после домена
        $pattern = $del.'^'
        ."([a-zA-Z0-9\-_./~]{1,})"
        .preg_quote("?", $del)
        ."([/\-_[\]a-zA-Z0-9.?=&%#~:;]{0,})"
        .$del."i";
        $result = preg_match_all($pattern, $other, $matches);
        if (isset($matches[0][0])) {
            $path = $matches[1][0];
            $query_fragment = $matches[2][0];
            $query_fragment_array = explode('#', $query_fragment);
            $return_array['query'] = $query_fragment_array[0];
            if (isset($query_fragment_array[1])) $return_array['fragment'] = $query_fragment_array[1];
        } else {
            $other_array = explode('#', $other);
            $path = $other_array[0];
            if (isset($other_array[1])) $return_array['fragment'] = $other_array[1];
        }
        if ($other != '' && $path != '') {
            $return_array['path'] = $path;
            $path_array = explode('/', $path);
            if ($path_array[count($path_array)-1] != '') {
                $return_array['path_file'] = $path_array[count($path_array)-1];
            }
            array_pop($path_array);
            $new_path = '';
            for ($i=0; isset($path_array[$i]); $i++) $new_path .= $path_array[$i].'/';
            if ($new_path != '') $return_array['path_dir'] = $new_path;
        }
        if (!isset($return_array['type'])) {
            $return_array['type'] = 'rel';
            if (isset($return_array['path_dir'])) if (substr($return_array['path_dir'], 0, 1) == '/') $return_array['type'] = 'root';
            if (isset($return_array['fragment']) && !isset($return_array['path']) && !isset($return_array['query'])) $return_array['type'] = 'fragment';
        }
        return $return_array;
    }

    /*
    —клеивает ссылку обратно
    ѕозимствовано и дополнено с http://ru.php.net/manual/ru/function.parse-url.php
    */
    public function glue($parsed = null)
    {
        if (!is_array($parsed)) return false;
        $uri = isset($parsed['scheme']) ? $parsed['scheme'].':'.((strtolower($parsed['scheme']) == 'mailto') ? '' : '//') : '';
        $uri .= isset($parsed['user']) ? $parsed['user'].(isset($parsed['pass']) ? ':'.$parsed['pass'] : '').'@' : '';
        $uri .= isset($parsed['host']) ? $parsed['host'] : '';
        $uri .= isset($parsed['port']) ? ':'.$parsed['port'] : '';
        if(isset($parsed['path']))
        {
            $uri .= (substr($parsed['path'], 0, 1) == '/') ? $parsed['path'] : ('/'.$parsed['path']);
        }
        $uri .= isset($parsed['query']) ? ( (trim($parsed['query']) != '') ? '?'.$parsed['query'] : '' ): '';
        $uri .= isset($parsed['fragment']) ? '#'.$parsed['fragment'] : '';
        return $uri;
    }

    /*
    –азбирает строку параметров URL в ассоциативный массив. ≈сли строка задана некоррекно, возвращает false.
    */
    public function parseQuery($query)
    {        if (!is_string($query)) return false;
        $nv = explode('&', $query);
        $get = Array();
        foreach ($nv as $nv_str) {        	$nv_arr = explode('=', $nv_str);        	if (isset($nv_arr[1])) $get[$nv_arr[0]] = rawurldecode($nv_arr[1]);
        	else {        		$valid = false;
        		break;
        	}        }
        return $get;
    }

    /*
    —обирает строку параметров URL из ассоциативного массива.
    */
    public function glueQuery($params)
    {
        if (!is_array($params)) return false;
        $query = '';
        foreach ($params as $key => $value) {            if ($query != '') $query .= '&';
            $query .= $key.'='.rawurlencode($value);        }
        return $query;
    }

}

?>