<?php
    /**
     * @package   Hippo Shortcode
     * @author    ThemeHippo http://www.themehippo.com
     * @copyright Copyright (c) 2013 - 2014 ThemeHippo
     * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
     */
    //no direct accees
    defined('_JEXEC') or die('resticted aceess');

    //======================================================================
    //
    //  [hippo-row id="" class=""]
    //  [hippo-column class=""]  bootstrap 3 grid class
    //
    //  [/hippo-column]
    //  [/hippo-row],
    //
    //======================================================================


    if (!function_exists('row_sc')) {
        $columnArray = array();

        function row_sc($atts, $content = "")
        {
            ///global $columnArray;
            $id = '';

            $params = shortcode_atts(array(
                'id'    => '',
                'class' => ''
            ), $atts);

            if ($params[ 'id' ])
                $id = 'id="' . $params[ 'id' ] . '"';

            //do_shortcode( $content );

            //Row
            $html = '<div class="row ' . $params[ 'class' ] . '" ' . $id . '>';
            //Columns
            //foreach ($columnArray as $key=>$value) $html .='<div class="' . $value['class'] . '">' . do_shortcode($value['content']) . '</div>';
            $html .= do_shortcode($content);
            $html .= '</div>';

            //$columnArray = array();
            return $html;
        }

        add_shortcode('hippo-row', 'row_sc');

        //Row Items
        function column_sc($atts, $content = "")
        {
            //global $columnArray;
            //$columnArray[] = array('class'=>$atts['class'], 'content'=>$content);
            $params = shortcode_atts(array(
                'id'    => '',
                'class' => ''
            ), $atts);
            return '<div class="' . $params[ 'class' ] . '">' . do_shortcode($content) . '</div>';

        }

        add_shortcode('hippo-column', 'column_sc');
    }