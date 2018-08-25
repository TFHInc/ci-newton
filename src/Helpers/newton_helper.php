<?php
if (!function_exists('newton')) {
    /**
     * Return an instance of the Newton Library.
     *
     * @return TFHInc\Newton\Newton
     */
    function newton(): TFHInc\Newton\Newton
    {
        $CI =& get_instance();

        if (!isset($CI->newton)) {
            $CI->newton = new TFHInc\Newton\Newton();
        } elseif (!$CI->newton instanceof TFHInc\Newton\Newton) {
            $CI->newton = new TFHInc\Newton\Newton();
        }

        return $CI->newton;
    }
}