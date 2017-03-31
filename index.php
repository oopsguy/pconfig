<?php
use Components\Config\Impl\ArrayConfig;



//$config = new \Components\Configuration\Impl\INIConfig('config.ini');

//var_dump($config['api#aliyun#db#oos#0']);

//function arr2ini(array $a, array $parent = array())
//{
//    $out = '';
//    foreach ($a as $k => $v)
//    {
//        if (is_array($v))
//        {
//            //subsection case
//            //merge all the sections into one array...
//            $sec = array_merge((array) $parent, (array) $k);
//            //add section information to the output
//            $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
//            //recursively traverse deeper
//            $out .= arr2ini($v, $sec);
//        }
//        else
//        {
//            //plain key->value case
//            $out .= "$k=$v" . PHP_EOL;
//        }
//    }
//    return $out;
//}
//
//$x = [
//    'section1' => [
//        'key1' => 'value1',
//        'key2' => 'value2',
//        'subsection' => [
//            'subkey' => 'subvalue',
//            'further' => ['a' => 5],
//            'further2' => ['b' => -5]]]];
//echo '<pre>';
//echo arr2ini($x);