<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * URL Shortener
 *
 * Based on Phil Sturgeon's Code Igniter Rest Server package to work as a URL shortener service.
 *
 * @link http://philsturgeon.co.uk/code/
*/

require APPPATH.'/libraries/REST_Controller.php';

class Url extends REST_Controller
{
	
    /**
     * Validates data 
     *
     * @param data A string that needs to be validated
     * @param data_type Type of data we are validating, defaulting to 'url' for now.
     *
     * @return True if string is a valid URL, false otherwise
     */

    function validate_data($data, $data_type)
    {
        if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $data)) 
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    

    /**
     * Returns a hash for a given URL. If no URL in database, generates a new hash
     *
     */
    function genhash_get()
    {
        if(!$this->get('url') || trim($this->get('url')) == '')
        {
           // $this->response(NULL, 400);
            $this->response(array('error' => 'Invalid command or missing parameters'), 404);
        }
 

        if ($this->get('url'))
        {
            $url = preg_replace("(https?://)", "",  $this->get('url'));

            if ($this->validate_data($url, 'url'))
            {
                $info = $this->url_model->fetch_hash_from_url($url);
            }
            else
            {
                $this->response(array('error' => 'Please specify a valid url in the format "www.website.com"'), 404);
            }
        }
       
        if ($info)
        {
            $this->response($info, 200); // 200 being the HTTP response code
        } 
        else
        {
            $this->response(array('error' => 'No information given parameters.'), 404);
        }
    }

    function genhash_post()
    { 

    }

    function genhash_put()
    { 

    }

    function genhash_delete()
    {  

    }

    function genurl_get()
    {
        if(!$this->get('hash') || trim($this->get('hash')) == '')
        {
            $this->response(NULL, 400);
        }
        
        if ($this->get('hash'))
        {
            $info = $this->url_model->fetch_url_from_hash($this->get('hash'));
        }
       
        if ($info)
        {
            if (isset($info['url']))
            {
                $this->response($info, 200); // URL exists for that hash
            }
            else
            {
                $this->response($info, 404); // Hash is not in our system. Return a 404
            } 
        } 
        else
        {
            $this->response(array('error' => 'No information given parameters.'), 404);
        }

    }

    function genurl_post()
    {


    }

    function genurl_put()
    {

    }

    function geturl_delete()
    {


    }

 
     
}