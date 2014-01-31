<?php
class pager{
function findStart($limit) {
    if ((!isset($_GET['p'])) || ($_GET['p'] == "1")) {
        $start = 0;
        $_GET['p'] = 1;
    } else {
        $start = ($_GET['p']-1) * $limit;
    }
    return $start;
}
  
  /*
   * int findPages (int count, int limit)
   * Returns the number of pages needed based on a count and a limit
   */
function findPages($count, $limit) {
     $pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
 
     return $pages;
}
 
/*
* string pageList (int curpage, int pages)
* Returns a list of pages in the format of "� < [pages] > �"
**/
function pageList($curpage, $pages)
{
    $page_list  = "";
 
    /* Print the first and previous page links if necessary */
    if (($curpage != 1) && ($curpage)) {
       $page_list .= "  <a href=\" ".$_SERVER['PHP_SELF']."?page=list_plans&p=1\" title=\"First Page\">Previous</a> ";
    }
 
    if (($curpage-1) > 0) {
       $page_list .= "<a href=\" ".$_SERVER['PHP_SELF']."?page=list_plans&p=".($curpage-1)."\" title=\"Previous Page\"><</a> ";
    }
 
    /* Print the numeric page list; make the current page unlinked and bold */
    for ($i=1; $i<=$pages; $i++) {
        if ($i == $curpage) {
            $page_list .= "<a style='background:#ccc;'"." href=''>".$i."</a>";
        } else {
            $page_list .= "<a href=\" ".$_SERVER['PHP_SELF']."?page=list_plans&p=".$i."\" title=\"Page ".$i."\">".$i."</a>";
        }
        $page_list .= " ";
      }
 
     /* Print the Next and Last page links if necessary */
     if (($curpage+1) <= $pages) {
        $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=list_plans&p=".($curpage+1)."\" title=\"Next Page\">Next</a> ";
     }
 
     if (($curpage != $pages) && ($pages != 0)) {
        $page_list .= "<a href=\"".$_SERVER['PHP_SELF']."?page=list_plans&p=".$pages."\" title=\"Last Page\">&raquo;</a> ";
     }
     $page_list .= "</td>\n";
 
     return $page_list;
}
  
/*
* string nextPrev (int curpage, int pages)
* Returns "Previous | Next" string for individual pagination (it's a word!)
*/
function nextPrev($curpage, $pages) {
 $next_prev  = "";
 
    if (($curpage-1) <= 0) {
        $next_prev .= "Previous";
    } else {
        $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=list_plans&p=".($curpage-1)."\">Previous</a>";
    }
 
        $next_prev .= " | ";
 
    if (($curpage+1) > $pages) {
        $next_prev .= "Next";
    } else {
        $next_prev .= "<a href=\"".$_SERVER['PHP_SELF']."?page=list_plans&p=".($curpage+1)."\">Next</a>";
    }
        return $next_prev;
    }
}
?> 