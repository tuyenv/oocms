<?php

namespace CoreBundle\Utility;

use CoreBundle\CoreCommonController;

class Pager
{
    public static function getPager(CoreCommonController $_this, $currentPage, $totalPages, $pageName, $queries)
    {
        $pager = array();

        $pager['first_href'] = '';
        if ($currentPage > 1) {
            $queries['page'] = 1;
            $pager['first_href'] = $_this->generateUrl($pageName, $queries);
        }
        $pager['prev_href'] = '';
        if ($currentPage > 1) {
            $queries['page'] = $currentPage - 1;
            $pager['prev_href'] = $_this->generateUrl($pageName, $queries);
        }

        $pager['next_href'] = '';
        if ($currentPage < $totalPages) {
            $queries['page'] = $currentPage + 1;
            $pager['next_href'] = $_this->generateUrl($pageName, $queries);
        }
        $pager['last_href'] = '';
        if ($currentPage < $totalPages) {
            $queries['page'] = $totalPages;
            $pager['last_href'] = $_this->generateUrl($pageName, $queries);
        }

        $pager['items'] = array();

        $minNumber = 1;
        if ($currentPage > 5) {
            $minNumber = $currentPage - 4;
            $tmp = array();
            $tmp['title'] = 1;
            $queries['page'] = 1;
            $tmp['href'] = $_this->generateUrl($pageName, $queries);
            $pager['items'][] = $tmp;

            $tmp = array();
            $tmp['title'] = '.....';
            $queries['page'] = '';
            $tmp['href'] = 'javascript:void(0);';
            $pager['items'][] = $tmp;

        }
        $maxNumber = $totalPages;
        if ($totalPages - $currentPage > 5) {
            $maxNumber = $currentPage + 4;
        }


        for ($i = $minNumber; $i < $maxNumber + 1; $i++) {
            $tmp = array();
            $tmp['title'] = $i;
            $queries['page'] = $i;
            $tmp['href'] = $_this->generateUrl($pageName, $queries);
            if ($i == $currentPage) {
                $tmp['href'] = '';
            }
            $pager['items'][] = $tmp;
        }


        if ($totalPages - $currentPage > 5) {

            $tmp = array();
            $tmp['title'] = '.....';
            $queries['page'] = '';
            $tmp['href'] = 'javascript:void(0);';
            $pager['items'][] = $tmp;

            $tmp = array();
            $tmp['title'] = $totalPages;
            $queries['page'] = $totalPages;
            $tmp['href'] = $_this->generateUrl($pageName, $queries);
            $pager['items'][] = $tmp;
        }

        return $pager;
    }
}