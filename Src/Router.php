<?php

namespace App\Src;

/**
 * Class Router
 * @package App\Src
 */
class Router {

    /**
     * Stocke l'ensembe des routes dans un tableau
     * @var array
     */
    static $routes = array();
    /**
     * Stocke l'ensemble des préfixes dans un tableau
     * @var array
     */
    static $prefixes = array();

    /**
     * Ajoute un prefix au Routing
     * @param string $url
     * @param string $prefix
     */
    public static function prefix(string $url, string $prefix)
    {
        self::$prefixes[$url] = $prefix;
    }

    /**
     * Permet de parser une url
     * @param string $url
     * @param Request $request
     * @return bool contenant les paramètres
     */
    public static function parse(string $url, Request $request): bool
    {
        $url = trim($url,'/');
        if(empty($url))
        {
            $url = Router::$routes[0]['url'];
        }
        else
            {
            $match = false;
            foreach(Router::$routes as $v){
                if(!$match && preg_match($v['redirreg'],$url,$match)){
                    $url = $v['origin'];
                    foreach($match as $k=>$v){
                        $url = str_replace(':'.$k.':',$v,$url);
                    }
                    $match = true;
                }
            }
        }
        $params = explode('/',$url);
        if(in_array($params[0],array_keys(self::$prefixes))){
            $request->prefix = self::$prefixes[$params[0]];
            array_shift($params);
        }
        $request->controller = $params[0];
        if (isset($params[1]))
        {
            if (strpos($params[1], '?') !== false)
            {
                $request->action = substr($params[1], 0, strpos($params[1], '?'));
            }
            else
            {
                $request->action = $params[1];
            }
        }
        else
        {
            $request->action = 'index';
        }
        //On controle si le prefixe admin est présent dans l'URL utilisarteur, et on le supprime(hacking)
        foreach (self::$prefixes as $k => $v) {
            if (stripos($request->action, $v.'_') === 0) {
                $request->prefix = $v;
                $request->action = str_replace($v.'_', '', $request->action);
            }
        }
        $request->params = array_slice($params,2);
        return true;
    }

    /**
     * Permet de connecter une url à une action particulière
     **/
    public static function connect($redir,$url){
        $r = array();
        $r['params'] = array();
        $r['url'] = $url;

        $r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/','${1}:(?P<${1}>${2})',$url);
        $r['originreg'] = str_replace('/*','(?P<args>/?.*)',$r['originreg']);
        $r['originreg'] = '/^'.str_replace('/','\/',$r['originreg']).'$/';
        // MODIF
        $r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/',':${1}:',$url);
        $r['origin'] = str_replace('/*',':args:',$r['origin']);

        $params = explode('/',$url);
        foreach($params as $k=>$v){
            if(strpos($v,':')){
                $p = explode(':',$v);
                $r['params'][$p[0]] = $p[1];
            }
        }

        $r['redirreg'] = $redir;
        $r['redirreg'] = str_replace('/*','(?P<args>/?.*)',$r['redirreg']);
        foreach($r['params'] as $k=>$v){
            $r['redirreg'] = str_replace(":$k","(?P<$k>$v)",$r['redirreg']);
        }
        $r['redirreg'] = '/^'.str_replace('/','\/',$r['redirreg']).'$/';

        $r['redir'] = preg_replace('/:([a-z0-9]+)/',':${1}:',$redir);
        $r['redir'] = str_replace('/*',':args:',$r['redir']);

        self::$routes[] = $r;
    }

    /**
     * Permet de générer une url à partir d'une url originale
     * controller/action(/:param/:param/:param...)
     * @param string $url
     * @return string
     */
    public static function url(string $url = ''): string
    {
        trim($url,'/');
        foreach(self::$routes as $v){
            if(preg_match($v['originreg'],$url,$match)){
                $url = $v['redir'];
                foreach($match as $k=>$w){
                    $url = str_replace(":$k:",$w,$url);
                }
            }
        }
        foreach(self::$prefixes as $k=>$v){
            if(strpos($url,$v) === 0){
                $url = str_replace($v,$k,$url);
            }
        }
        return BASE_URL.DS.$url;
    }

    /**
     * @param string $url
     * @return string
     */
    public static function webroot(string $url): string
    {
        trim($url,'/');
        if (is_null($url)) {
            return null;
        } else {
            return BASE_URL.DS.$url;
        }
    }

}
