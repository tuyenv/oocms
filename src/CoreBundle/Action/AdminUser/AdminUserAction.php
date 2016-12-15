<?php

namespace CoreBundle\Action\AdminUser;


use CoreBundle\CoreAdminController;
use Symfony\Component\HttpFoundation\Request;

class AdminUserAction
{
    public static function GET(CoreAdminController $_this, Request $request)
    {
        //access denied
        if (!$_this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $_this->redirectToRoute('admin_login_page');
        }

        //access denied
        if (!$_this->isGranted('ROLE_SUPER_ADMIN')) {
            return $_this->_adminError403Action();
        }


        $reservation = $request->query->get('reservation', '');
        $email = $request->query->get('email', '');

        $reservation_data = $_this->_getReservationTime(
          $reservation,
          time() - 365 * 24 * 60 * 60,
          time() + 1 * 24 * 60 * 60
        );

        $perPage = 10;
        $perPage = intval($request->query->get('length', $perPage));
        $currentPage = intval($request->query->get('page', 1));
        $dql = 'SELECT u.id, u.email, u.createdAt, u.status
        FROM CoreBundle:User u
        WHERE u.createdAt > :from
        AND u.createdAt < :to
        AND u.email LIKE :email
        ORDER BY u.createdAt DESC';
        $result = $_this->_executePagerDQL(
          $dql,
          array(
            'from' => $reservation_data->from,
            'to' => $reservation_data->to,
            'email' => '%'.$email.'%',
          ),
          $perPage,
          $currentPage
        );


        $dql = 'SELECT COUNT(u.id) AS total
        FROM CoreBundle:User u
        WHERE u.createdAt > :from
        AND u.createdAt < :to
        AND u.email LIKE :email
        ORDER BY u.createdAt DESC';
        $result2 = $_this->_executeDQL(
          $dql,
          array(
            'from' => $reservation_data->from,
            'to' => $reservation_data->to,
            'email' => '%'.$email.'%',
          )
        );

        $arr = $result;
        $total = $result2[0]['total'];
        $items = array();
        foreach ($arr as $key => $value) {
            $tmp = array();

            $tmp['id'] = $value['id'];
            $tmp['email'] = $value['email'];
            $tmp['createdAt'] = date('Y-m-d H:i:s', $value['createdAt']);
            $tmp['status'] = $value['status'];
            $items[] = $tmp;

        }

        $queries = array();
        $queries['reservation'] = $reservation;
        $queries['email'] = $email;
        $pager = $_this->_getPager(
          $currentPage,
          ceil($total / $perPage),
          'admin_user_page',
          $queries
        );

        $data = array(
          'reservation' => date('m/d/Y', $reservation_data->from).' - '.date(
              'm/d/Y',
              $reservation_data->to
            ),
          'currentPage' => $currentPage,
          'totalPages' => ceil($total / $perPage),
          'pager' => $pager,
          'items' => $items,
        );

        return $_this->render(
          '@admin/admin_user/admin_user_page.html.twig',
          array(
            'data' => $data,
          )
        );
    }
}