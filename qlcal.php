<?php
/**
 * Plugin Name: qlcal
 */

defined('ABSPATH') or die ('No script kiddies please!');

add_shortcode('qlcal', 'qlcal');

function qlcal()
{
  $category = get_cat_ID('Kalender');
  $posts = get_posts(array('category' => $category, 'numberposts' => -1));
  
  $ret = '';
  $today = strtotime('midnight');
  //$ret .= 'found '.count($posts).' posts;';
  $bydate = array();
  foreach ($posts as $post)
  {
    $daymonth = null;
    $tags = get_the_tags($post->ID);
    if(!is_array($tags))
    {
      //$ret .= 'Failed to get tags for post #'.$post->ID.'<br>';
      continue;
    }
    foreach ($tags as $tag)
    {
      if(preg_match('#([0-3][0-9])/([0-1][0-9])(:([a-zA-Z ]*))?#', $tag->name, $matches))
      {
        $date = strtotime($matches[2].'/'.$matches[1].'/'.date('Y', $today));
        if($date === false)
        {
          //$ret .= 'Failed to parse 1: '.$tag->name.'<br>';
          continue;
        }
        if ($date < $today)
          $date = strtotime($matches[2].'/'.$matches[1].'/'.(intval(date('Y', $today))+1));
        //echo $matches[4].';';
        if ($matches[4])
        {
          $date = strtotime($matches[4], $date);
          if($date === false)
          {
            //$ret .= 'Failed to parse 2: '.$tag->name.'<br>';
            continue;
          }
        }
        $daymonth = date('Y m d', $date);
        break;
      }
      else if(preg_match('#([1-5]|(last))/((mon)|(tue)|(wed)|(thu)|(fri)|(sat)|(sun)|(week)|(day))/([0-1][0-9])#', $tag->name, $matches))
      { //1,3,13
        $v1 = array('1' => 'first', '2' => 'second', '3' => 'third', '4' => 'fourth', '5' => 'fifth', 'last' => 'last')[$matches[1]];
        $v2 = $matches[3];
        $v3 = array('01' => 'january', '02' => 'febuary', '03' => 'march', '04' => 'april', '05' => 'may', '06' => 'june', 
                    '07' => 'july', '08' => 'august', '09' => 'septermber', '10' => 'october', '11' => 'november', '12' => 'december')[$matches[13]];
        $rel = null;
        if ($v2 == 'week')
        {
          $rel = '-6 days';
          $v2 = 'sun';
        }
        $date = strtotime($v1.' '.$v2.' of '.$v3.' '.$year);
        if ($date === false)
        {
          //$ret .= 'Failed to parse1: '.$tag->name.'<br>';
          continue;
        }
        if ($rel)
          $date = strtotime($rel, $date);
          
        if ($date === false)
        {
          //$ret .= 'Failed to parse2: '.$tag->name.'<br>';
          continue;
        }
        
        //echo 'date: '.date('Y m d', $date).' ('.$date.')<br>';
        //echo 'today: '.date('Y m d', $today).' ('.$today.')<br>';
        if ($date < $today)
        {
          $date = strtotime($v1.' '.$v2.' of '.$v3.' '.(intval(date('Y', $today)) + 1));
          if($rel)
            $date = strtotime($rel, $date);
        }
        $daymonth = date('Y m d', $date);
        //$date = 'Parsed '.$tag->name.': '.date('Y-m-d', $date).'<br>';
        //$ret .= $date;
        break;
        
        //break;
      }
    }
    if($daymonth === null)
      continue;
    $a = array();
    if (in_array($daymonth, array_keys($bydate)))
      $a = $bydate[$daymonth];
    $a[] = $post;
    $bydate[$daymonth] = $a;
  }
  
  ksort($bydate, SORT_STRING);

  $ret .= '<table style="border: 0em;"><tbody>';
  foreach ($bydate as $md => $posts)
  {
    foreach ($posts as $post)
    {
      $s = explode(' ', $md);
      $ret .= '<tr><td style="border: 0em;width:1px;white-space:nowrap;vertical-align:top">'.$s[2].'.'.$s[1].'.'.$s[0].'</td><td style="border: 0em;"><a href="'.get_permalink($post).'">'.$post->post_title.'</a></td></tr>';
    }
  }
  $ret .= '</tbody></table>';
  
  return $ret;
}
?>
