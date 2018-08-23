<?php
if (!function_exists('newton')) {
    /**
     * Return an instance of the Newton Library.
     *
     * @return Newton
     */
    function newton(): Newton
    {
        $CI =& get_instance();

        if (!isset($CI->newton)) {
            $CI->load->library('Newton/src/Newton');
        } elseif (!$CI->newton instanceof Newton) {
            $CI->load->library('Newton/src/Newton');
        }

        return $CI->newton;
    }
}