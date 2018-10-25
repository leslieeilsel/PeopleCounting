<?php

class Chart extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 实时学院入馆人数
     *
     * @return array
     */
    public function getRealTimeCount()
    {
        $deptResult = [];
        $query = $this->db->query("SELECT dept,COUNT(dept) AS deptcnt FROM rtpass WHERE to_days(VisitTime) = to_days(now()) AND dept != '未知部门' GROUP BY dept ORDER BY deptcnt ASC");
        foreach ($query->result_array() as $row) {
            $deptResult[] = $row;
        }

        $query->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识

        if (!empty($deptResult)) {
            foreach ($deptResult as $k => $v) {
                $xueyuan = '';
                $xueyuan = substr($v['dept'], -6);
                if ($xueyuan != '学院') {
                    unset($deptResult[$k]);
                }
            }
        }
        $deptName = array_column($deptResult, 'dept');
        $deptValue = array_column($deptResult, 'deptcnt');

        return [
            'dept_name' => $deptName,
            'dept_value' => $deptValue
        ];
    }

    /**
     * 判断是否跳转到欢迎页
     *
     * @return number
     */
    public function isOrNotReplaceWelcomePage()
    {
        $query = $this->db->query("SELECT VisitTime FROM rtpass ORDER BY VisitTime DESC LIMIT 1");
        $latestVisitTime = $query->row()->VisitTime;

        $query->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识

        $file_path = "resource/files/last-date.txt";
        if (file_exists($file_path)) {
            $str = file_get_contents($file_path);//将整个文件内容读入到一个字符串中
            if ($str == $latestVisitTime) {
                return 0;
            } else {
                file_put_contents($file_path, $latestVisitTime);
                return 1;
            }
        } else {
            file_put_contents($file_path, $latestVisitTime);
            return 0;
        }
    }

    /**
     * 获取今年总人数和当天总人数
     *
     * @return array
     */
    public function getWelcomeCount()
    {
        $countAllQuery = $this->db->query("SELECT COUNT(*) AS count FROM rtpass WHERE VisitTime >= '2018-01-01 00:00:00.000' AND dept != '未知部门'");
        $countAll = $countAllQuery->row()->count;
        $countAllQuery->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识

        $countTodayQuery = $this->db->query("SELECT COUNT(*) AS count FROM rtpass WHERE to_days(VisitTime) = to_days(now()) AND dept != '未知部门'");
        $countToday = $countTodayQuery->row()->count;
        $countTodayQuery->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识

        return [
            'countAll' => $countAll,
            'countToday' => $countToday
        ];
    }

    /**
     * 实时学院入馆人数和实时学生入馆人数
     *
     * @return array
     */
    public function getPercentData()
    {
        /**
         * 实时学院入馆人数
         */
        $deptResult = [];
        $deptQuery = $this->db->query("SELECT dept,COUNT(dept) AS deptcnt FROM rtpass WHERE to_days(VisitTime) = to_days(now()) AND dept != '未知部门' GROUP BY dept");
        foreach ($deptQuery->result_array() as $row) {
            $deptResult[] = $row;
        }

        $deptQuery->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识

        $deptResultItem = [];
        if (!empty($deptResult)) {
            $deptResultArr = [];
            foreach ($deptResult as $k => $v) {
                $xueyuan = '';
                $xueyuan = substr($v['dept'], -6);
                if ($xueyuan == '学院') {
                    $deptResultArr['name'] = $v['dept'];
                    $deptResultArr['value'] = $v['deptcnt'];
                    $deptResultItem[] = $deptResultArr;
                }
            }
        }

        /**
         * 实时学生入馆人数
         */
        $studentResult = [];
        $studentQuery = $this->db->query("SELECT `type`,COUNT(`type`) AS typecnt FROM rtpass WHERE to_days(VisitTime) = to_days(now()) AND dept != '未知部门' GROUP BY `type` ORDER BY typecnt ASC");
        foreach ($studentQuery->result_array() as $row) {
            $studentResult[] = $row;
        }

        $studentQuery->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识
        $studentResultItem = [];
        $typeArr = [];
        if (!empty($studentResult)) {
            $studentResultArr = [];
            foreach ($studentResult as $k => $v) {
                $sheng = '';
                $sheng = substr($v['type'], -3);
                if ($sheng == '生' || $sheng == '工') {
                    $typeArr[] = $v['type'];
                    $studentResultArr['name'] = $v['type'];
                    $studentResultArr['value'] = $v['typecnt'];
                    $studentResultItem[] = $studentResultArr;
                }
            }
        }

        return [
            'deptResultItem' => $deptResultItem,
            'studentResultItem' => $studentResultItem,
            'typeArr' => $typeArr
        ];
    }

    /**
     * 当日分时段人数统计
     *
     * @return array
     */
    public function geTimeslotData()
    {
        $timeslotData = [];
        $hour = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00','22:30'];
        $query = $this->db->query("SELECT DATE_FORMAT( VisitTime, '%H' ) AS hours, count( * ) AS count FROM rtpass WHERE to_days( VisitTime ) = to_days( now( ) ) GROUP BY hours");
        foreach ($query->result_array() as $row) {
            $timeslotData[] = $row['count'];
        }
        $timeslotData = array_pad($timeslotData, 16, '');
        $query->free_result();  // 释放掉查询结果所占的内存，并删除结果的资源标识
        
        return [
            'hour' => $hour,
            'timeslotData' => $timeslotData
        ];
    }
}
