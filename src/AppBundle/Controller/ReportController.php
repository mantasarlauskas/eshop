<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.12.8
 * Time: 22.39
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Orders;
use AppBundle\Entity\Product;
use AppBundle\Entity\Report;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReportController extends Controller
{
    /**
     * @Route("/report/list", name="report.list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listReport()
    {
        $report = $this->getDoctrine()->getRepository(Report::class)
            ->findAll();

        return $this->render('report/list.html.twig', [
            'logs' => $report
        ]);
    }

    /**
     * @Route("/report/user", name="report.user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listUserReport()
    {
        $user = $this->getUser();

        $report = $this->getDoctrine()->getRepository(Report::class)
            ->findBy(array('user' => $user->getId()));

        return $this->render('report/list.html.twig', [
            'logs' => $report
        ]);
    }
}