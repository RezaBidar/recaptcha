<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * reverse unicode string
 * @param  string $str 
 * @return string      
 */
function persian_strrev($str)
{
	preg_match_all('/./us', $str, $ar);
	return join('', array_reverse($ar[0]));
}

function generate_persian_str($length)
{
	$pool = array('ا' , 'ب' , 'پ' , 'ت' , 'ث' , 'ج' , 'چ' , 'ح' , 'خ' , 'د' , 'ذ' , 'ر' , 'ز' , 'ژ' , 'س' , 'ش' , 'ص' , 'ض' , 'ط' , 'ظ' , 'ع' , 'غ' , 'ف' , 'ق' , 'ک' , 'گ' , 'ل' , 'م' , 'ن' , 'و' , 'ه' , 'ی' ) ;
	$word = '';
	for ($i = 0, $mt_rand_max = sizeof($pool) - 1; $i < $length; $i++)
	{
		$word .= $pool[mt_rand(0, $mt_rand_max)];
	}
	return $word ;
}

function size_limit($str = '', $max = 0, $min = 100)
{
	$ar = array();
	preg_match_all('/./us', $str, $ar);
	if(sizeof($ar[0]) >= $min && sizeof($ar[0]) <= $max)
		return TRUE ;
	return FALSE ;
}
