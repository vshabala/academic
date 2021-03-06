<?php
namespace Oro\Bundle\IssueBundle\Controller\Dashboard;

use Oro\Bundle\IssueBundle\Entity\Issue;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{

    /**
     *
     * @Route("/issue", name="dashboard_issue")
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template("OroIssueBundle:Dashboard:userIssue.html.twig")
     */
    public function userIssueAction()
    {
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig('user_issue');
        $widgetAttr['userId'] = $this->getUser()->getId();
        return  $widgetAttr;

    }

    /**
     * @return array
     *
     * @Route("/chart/status", name="issue_status_chart")
     * @Acl(
     *      id="oro_issue_view",
     *      type="entity",
     *      class="OroIssueBundle:Issue",
     *      permission="VIEW"
     * )
     * @Template("OroIssueBundle:Dashboard:issueStatus.html.twig")
     */
    public function statusChartAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $items  = $manager->getRepository('OroIssueBundle:Issue')->countIssuesPerStatus();

        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig('issue_status');
        $widgetAttr['chartView']= $this->get('oro_chart.view_builder')
            ->setArrayData($items)
            ->setOptions(
                array(
                    'name' => 'bar_chart',
                    'data_schema' => array(
                        'label' => array('field_name' => 'label'),
                        'value' => array('field_name' => 'number')
                    ),
                    'settings' => array('xNoTicks' => count($items)),
                )
            )
            ->getView();
        return $widgetAttr;

    }
}
