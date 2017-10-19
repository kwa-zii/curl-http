<?php
/**
 * One of the curl - based HTTP libraries implemented in PHP
 *
 * @category Crul_Http
 * @package  Qinqw\Curl\Http
 * @author   Kevin <qinqiwei@hotmail.com>
 * @license  Apache License V2
 * @link     https://github.com/qinqw/curl-http
 */

namespace Qinqw\Curl\Http;

/**
 * The Curl wrapper class library for HTTP requests
 * 
 * @category Curl_Http
 * @package  Qinqw\Curl\Http
 * @author   Kevin <qinqiwei@hotmail.com>
 * @date     2017-06-01
 * @license  Apache License V2
 * @link     https://github.com/qinqw/curl-http
 */

class Request
{
    private $_ch;  //Curl 实例
    private $_flag_if_have_run; //是否已经之行 curl_exec
    
    /**
     * 构造函数
     *
     * @param string $url 数据格式 'http://192.168.88.197:8080/api/jobs'
     *
     * @return void
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function __construct($url)
    {
        $this->_ch = curl_init($url);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
    }
    
    /**
     * 关闭curl连接
     *
     * @return void
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function close()
    {
        curl_close($this->_ch);
    }
    
    /**
     * 析构函数
     *
     * @return void
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function __destruct()
    {
        $this->close();
    }
    
     /**
      * 设置超时
      *
      * @param int $timeout 超时时间 秒

      * @return $mix
      *
      * @author: Kevin <qinqiwei@hotmail.com>
      */
    public function setTimeOut($timeout)
    {
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, intval($timeout));
        return $this;
    }
    
    /**
     * 设置重定向
     *
     * @param int $referer 格式 URL
     *
     * @return $mix
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function setReferer($referer)
    {
        if (!empty($referer)) {
            curl_setopt($this->_ch, CURLOPT_REFERER, $referer);
        }
        return $this;
    }
    
    /**
     * 从文件载入cookie
     *
     * @param string $cookie_file 文件名
     *
     * @return $curl
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function loadCookie($cookie_file)
    {
        curl_setopt($this->_ch, CURLOPT_COOKIEFILE, $cookie_file);
        return $this;
    }

    /**
     * 保存cookie到文件
     *
     * @param string $cookie_file 文件名
     *
     * @return $mix
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function saveCookie($cookie_file = "")
    {
        //设置缓存文件，例如a.txt
        if (empty($cookie_file)) {
            $cookie_file = tempnam('./', 'cookie');
        }
        curl_setopt($this->_ch, CURLOPT_COOKIEJAR, $cookie_file);
        return $this;
    }
    
    /**
     * 执行curl请求
     *
     * @return mixed
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function exec()
    {
        $str = curl_exec($this->_ch);
        $this->_flag_if_have_run = true;
        return $str;
    }
        
    /**
     * 执行GET请求
     *
     * @param mixed $url     请求地址
     * @param mixed $headers http请求头
     *
     * @return mixed
     */
    public function get($url, $headers = [])
    {
        $this->requests($url, "GET", "", $headers);
    }
        
    /**
     * 执行POST请求
     *
     * @param mixed $url     请求地址
     * @param mixed $params  请求参数request Body
     * @param mixed $headers http请求头
     *
     * @return mixed
     */
    public function post($url, $params, $headers = [])
    {
        $this->requests($url, "POST", $params, $headers);
    }
    
    /**
     * 执行PUT请求
     *
     * @param mixed $url     请求地址
     * @param mixed $params  请求参数request Body
     * @param mixed $headers http请求头
     *
     * @return mixed
     */
    public function put($url, $params, $headers = [])
    {
        $this->requests($url, "PUT", $params, $headers);
    }
    
    /**
     * 执行DELETE请求
     *
     * @param mixed $url     请求地址
     * @param mixed $params  请求参数request Body
     * @param mixed $headers http请求头
     *
     * @return mixed
     */
    public function delete($url, $params, $headers = [])
    {
        $this->requests($url, "DELETE", $params, $headers);
    }
    
    /**
     * 设置Crul请求信息
     *
     * @param string $URL     地址
     * @param string $type    请求类型 GET,POST,PUT,DELETE
     * @param string $params  请求参数 jsonstr 或者 urlparamsstr
     * @param array  $headers http请求头
     *
     * @return curl $this->_ch
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function requests($URL, $type, $params, $headers=[])
    {
        //$ch = curl_init();
        $timeout = 25;
        if ($URL != "") {
            curl_setopt($this->_ch, CURLOPT_URL, $URL);                     //请求地址
        }
        if ($headers==[]) {
            $headers = array('Content-type: application/json');
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
        } elseif (is_array($headers)&&(sizeof($headers)>0)) {
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($this->_ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);             //是否验证ssl证书
        switch ($type) {
        case "GET":
            curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
            break;
        case "POST":
            curl_setopt($this->_ch, CURLOPT_POST, true);
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $params);
            break;
        case "PUT":
            curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $params);
            break;
        case "DELETE":
            curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $params);
            break;
        }
        return $this;
    }

    /**
     * 获取http请求基础信息
     *
     * @return array httpheader
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function getInfo()
    {
        if ($this->_flag_if_have_run == true) {
            return curl_getinfo($this->_ch);
        } else {
            throw new Exception("exec first!");
        }
    }
    
    /**
     * 获取http返回 状态代码
     *
     * @return string http代码
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function getHttpCode()
    {
        if ($this->_flag_if_have_run == true) {
            return curl_getinfo($this->_ch, CURLINFO_HTTP_CODE);
        } else {
            throw new Exception("exec first!");
        }
    }
    
    /**
     * 获取http响应信息头
     * 
     * @return array Http response headers
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function getHttpHeaders()
    {
        if ($this->_flag_if_have_run == true) {
            $str_headers = curl_getinfo($this->_ch, CURLINFO_HEADER_OUT);
            $tmp_arr = explode("\n", $str_headers);
            $arr_headers = [];
            unset($tmp_arr[0]);
            foreach ($tmp_arr as $k => $v) {
                $tmp_header_item = explode(":", $v);
                if (sizeof($tmp_header_item)>1) {
                    $tmp_header_key = trim($tmp_header_item[0]);
                    $tmp_header_value = trim($tmp_header_item[1]);
                    $arr_headers[$tmp_header_key] = $tmp_header_value;
                }
            }
            
            return $arr_headers;
        } else {
            throw new Exception("exec first!");
        }
    }

    /**
     * Auth_Basic 认证方式 设置用户名,密码
     *
     * @param string $username 用户名
     * @param string $password 密码
     *
     * @return string http代码
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function setUsrpwd($username, $password)
    {
        curl_setopt($this->_ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($this->_ch, CURLOPT_USERPWD, $username.":".$password);
        return $this;
    }

    /**
     * 设置代理服务器
     *
     * @param string $proxy 代理数据格式 '68.119.83.81:27977'
     *
     * @return $this
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function setProxy($proxy)
    {
        curl_setopt($this->_ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->_ch, CURLOPT_PROXY, $proxy);
        return $this;
    }
    
    /**
     * 使用代理服务器时，设置请求来源地址IP
     *
     * @param string $ip IP数据格式 '68.119.83.81'
     *
     * @return string ip
     *
     * @author: Kevin <qinqiwei@hotmail.com>
     */
    public function setIp($ip)
    {
        if (!empty($ip)) {
            $headers = array("X-FORWARDED-FOR:".$ip, "CLIENT-IP:".$ip);
            curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
        }
        return $ip;
    }
}
