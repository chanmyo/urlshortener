<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class url_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Fetch Long URL given a hash
     *
     * @param hash Random hash
     *
     * @return Return a URL, or FALSE if hash is not in the database
     */
    function fetch_url_from_hash($hash)
    {
        $query = $this->db->get_where('urls', array('hash' => $hash), 1, 0);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return array
            (
                'status' => 'Existing URL',
                'url' => $row->long_url 
            );
        }
        else
        {
            return array
            (
                'status' => 'No URL associated with hash' 
            );
        };
    }

    /**
     * Fetch hash given a URL in the form www.website.com
     *
     * @param url Website URL
     *
     * @return Return a hash, or FALSE if url is not in our database
     */
    function fetch_hash_from_url($url)
    {
        $query = $this->db->get_where('urls', array('long_url' => $url), 1, 0);
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return array
            (
                'status' => 'Hash points to Existing URL',
                'hash' => $row->hash 
            );
             
        }
        else 
        {
            // No hash for URL - generate a new one
            $hash = $this->generate_hash($url);
            return array
            (
                'status' => 'No hash associated with URL, generating a new one',
                'hash' => $hash
            ); 
        }
    }

    /**
     * Generates a new hash given a URL, saves into DB
     *
     * @param url A website url to shorten
     * @param length Length of hash, defaults to 4
     *
     * @return A random string used for the short URL (domain/hash)  
     */
    function generate_hash($url, $length = 4)
    {
        $hash = $this->generate_random_string($length);

        // Save
        $data = array
        (
            'long_url' => $url,
            'hash' => $hash 
        );
        $this->db->insert('urls', $data);
        return $hash;
    }

    /**
     * Generates a random string
     *
     * @param length Number
     *
     * @return Random string
     */
    function generate_random_string($length)
    { 
        $random_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        return $random_string; 
    }
}
?>