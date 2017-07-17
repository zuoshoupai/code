<?php

//冒泡排序
function pao_sort($a)
{
    $i = count($a);
    if($i==o)
    {
        return 0;
    }
    for($j=0;$j<$i;$j++)
    {
        for($k=0;$k<($i-$j-1);$k++)
        {
            if($a[$k]<$a[$k+1])
            {
                $temp = $a[$k];
                $a[$k] = $a[$k+1];
                $a[$k+1] = $temp;
            }
        }
    }
    return $a;
}
//快速排序
function fast_sort($a)
{
    if(count($a)==0)
    {
        return $a;
    }
    $m = $a[0];
    $l = $r = array();
    for($i=1;$i<count($a);$i++)
    {
        if($a[$i]<$m)
        {
            $l[] = $a[$i];
        }else{
            $r[] = $a[$i];
        }
    }
    $l = fast_sort($l);
    $r = fast_sort($r);
    return array_merge($l,array($m),$r);
}
//插入排序
function insert_sort($a)
{
     for($i=1;$i<count($a);$i++)
     {
         $temp = $a[$i];
         for($j=$i-1;$j>=0&&$a[$j]>$temp;$j--)
         {
              $a[$j+1] = $a[$j];
         } 
         $a[$j+1] = $temp;
     }
     return $a;
}
//希尔排充
function shell_sort($a)
{
    $l = count($a);
    $d = floor($l/2);
    while($d>=1)
    {
        for($i=$d;$i<$l;$i++)
        {
            $t = $a[$i];
           for($j=$i-$d;$j>=0 && $a[$j]>$t;$j-=$d)
            {
                $a[$j+$d] = $a[$j]; 
            }
            $a[$j+$d] = $t;
        }
        $d = floor($d/2);
    }
    return $a;
}