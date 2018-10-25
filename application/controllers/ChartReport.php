<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ChartReport extends CI_Controller
{
    public function index()
    {
        $result = $this->chart->getRealTimeCount();

        $data['deptName'] = $result['dept_name'];
        $data['deptValue'] = $result['dept_value'];

        $this->load->view('real_time_count', $data);
    }

    public function getLastVisitTime()
    {
        $result = $this->chart->isOrNotReplaceWelcomePage();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['state' => $result]));
    }

    public function percent()
    {
        $data = $this->chart->getPercentData();

        $this->load->view('dept_student_percent', $data);
    }

    public function timeslot()
    {
        $data = $this->chart->geTimeslotData();

        $this->load->view('timeslot_count', $data);
    }
}
