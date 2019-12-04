<?php


namespace MVC\Classe;


/**
 * Class Response
 *
 * example use:
 * $data = array('a','b','c');
 *
 * Three Way to send a request
 *
 * $request = new Response('http://myurl','mymethod');
 * $request->addContent($data);
 * $request->send();
 *
 * OR
 *
 * $request = new Response('http://myurl');
 * (
 * $request->createContext('mymethod')
 * $request->addContent($data);
 * $request->send();
 * ) OR (
 * $request->get($data);
 * $request->post($data);
 * $request->put($data);
 * $request->delete($data);
 *
 *
 * OR
 *
 * $request = new Response();
 * $request->setUrl('http://myurl')->get($data)
 * $request->setUrl('http://myurl')->post($data)
 * $request->setUrl('http://myurl')->put($data)
 * $request->setUrl('http://myurl')->delete($data)
 *
 * @package MVC\Classe
 */
class HttpMethodRequete
{
    protected $url;
    protected $options;
    protected $method;
    protected $data;
    protected $content;

    /**
     * Response multi-constructor.
     */
    public function __construct()
    {
        $argumentFunction = func_get_args();
        $nbParamsFunction = func_num_args();
        if (method_exists($this, $function = '__construct' . $nbParamsFunction)) {
            call_user_func_array(array($this, $function), $argumentFunction);
        }
    }

    /**
     * @return $this
     */
    public function __construct0()
    {
        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function __construct1($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Response constructor.
     * @param $url URI
     * @param $method POST,...
     * @param $options
     * @return $this
     */
    public function __construct2($url, $method)
    {
        $this->url = $url;
        $this->method = $method;
        // utilisez 'http' même si vous envoyez la requête sur https:// ...
        $this->options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => $method,
            )
        );
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /*
        public function setGetParamsUrl($url, $params = array())
        {
            $this->url = $url . (strpos($url, '?') ? '&' : '?') . http_build_query($params);
            return $this;
        }
    */
    public function send()
    {

//        /** Pour utiliser ce code il faut mettre la variable allow_furl_open a ON dans php.ini */
//
//        ob_start();
//        Dumper::dump($this->options);
//        Dumper::dump($this->url);
//        $text = ob_get_clean();
//        Logger::addLog('fopen',$text);
//
//        $context = stream_context_create($this->options);
//        $result = file_get_contents($this->url, false, $context);
//        if ($result === FALSE) {
//            /* Handle error */
//            return false;
//        } else {
//            return true;
//        }

        $curl_cmd = "curl -i -X $this->method -H 'Content-Type: application/json' -d '$this->content' $this->url";

        /** Pour utiliser ce code il faut utiliser la variable curl.cainfo dans php.ini */
        Logger::addLog('curl', $curl_cmd);

        $curl = curl_init($this->url);
        //curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->data));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            return false;
        } else {
            return true;
        }

        /** Pour utiliser ce code il faut mettre la variable safe_mode a ON dans php.ini */
        //exec($curl_cmd);


    }

    /**
     * @param $data Array
     */
    public function addContent($data)
    {
        $this->data = $data;
        $this->content = json_encode($data);
        $this->options['http']['content'] = http_build_query($data);
        return $this;
    }

    public function replaceContext($method)
    {
        $this->method = $method;
        return $this->createContext($method);
    }

    public function createContext($method)
    {
        $this->method = $method;
        // utilisez 'http' même si vous envoyez la requête sur https:// ...
        $this->options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => $method,
            )
        );
        return $this;
    }

    public function get($params = array())
    {
        return $this->replaceContext('GET')->addContent($params)->send();
    }

    public function post($params = array())
    {
        return $this->replaceContext('POST')->addContent($params)->send();
    }

    public function put($params = array())
    {
        return $this->replaceContext('PUT')->addContent($params)->send();
    }

    public function delete($params = array())
    {
        return $this->replaceContext('DELETE')->addContent($params)->send();
    }

}