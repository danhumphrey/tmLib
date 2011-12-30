<?php
/**
  * HttpPathVars class
  * 
  * @package tmLib
  * @subpackage Http
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * HttpPathVars class for retrieving variables from a url path.
 * 
 * @package tmLib
 * @subpackage Http
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class HttpPathVars {
    private $baseUrl;
    private $baseScript;
    private $fragment;
    private $pathVars;

    /**
     * Constructs the HttpPathVars class 
     * @access public
     * @param string $baseUrl the directory path to the current script
     * @param string $baseScript the filename of the current script
     */
    function __construct ($baseUrl, $baseScript) {
        $this->baseUrl=$baseUrl;
        $this->baseScript=$baseScript;
        $this->pathVars=array();
        $this->validate();
        $this->fetchFragment();
        $this->parseFragment();
    }
    /**
     * validates the $baseUrl and $baseScript vars
     * @access private
     * @throws Exception
     */
    private function validate() {
        if (!strstr($_SERVER['REQUEST_URI'],$this->baseUrl))
        {
            throw new Exception('$baseUrl is invalid: '.$this->baseUrl );
        }
    }
    /**
    * Strips out $this->baseUrl and $this->baseScript from $_SERVER['REQUEST_URI']
    * @return void
    * @access private
    */
    private function fetchFragment () {
        if ( $this->baseUrl != '/' )
        {
            $this->fragment=str_replace($this->baseUrl,'',$_SERVER['REQUEST_URI']);
            $this->fragment=str_replace($this->baseScript,'',$this->fragment);
        }
        else
        {
            $this->fragment=$_SERVER['REQUEST_URI'];
        }
    }

    /**
    * Parses the fragment into variables
    * @return void
    * @access private
    */
    private function parseFragment () {
        
        if ( strstr ($this->fragment,'/') ) {
            $vars=explode('/',$this->fragment);
            foreach ($vars as $var) {
                if ( $var == '' ){
                    continue;
                }
                if ( strstr ($var,'=') ){
                    continue;
                }
                if ( strstr ($var,'&') ){
                    continue;
                }
                    
                if ( strstr ($var,'?') ){
                    continue;
                }
                    
                $this->pathVars[]=$var;
            }
        }
        else
        {   
            $fc = substr($this->fragment, 0 ,1);
            if($fc !== '?' && $fc !== '#'){
                 $this->pathVars[]=$this->fragment; 
            } 
        }
    }

    /**
    * Iterator for path vars
    * @return mixed
    * @access public
    */
    function fetch () {
        $var = each ( $this->pathVars );
        if ( $var ) {
            return $var['value'];
        } else {
            reset ( $this->pathVars );
            return false;
        }
    }

    /**
    * Returns $this->pathVars
    * @return array
    * @access public
    */
    function fetchAll () {
        return $this->pathVars;
    }

    /**
    * Return a value from $this->pathVars given it's index
    * @param int the index of this->pathVars to return
    * @return string
    * @access public
    */
    function fetchByIndex ($index) {
        if ( isset ($this->pathVars[$index]) )
            return $this->pathVars[$index];
        else
            return false;
    }
    /**
    * Returns the number of variables found
    * @return int
    * @access public
    */
    function size () {
        return count ( $this->pathVars );
    }
}
?>