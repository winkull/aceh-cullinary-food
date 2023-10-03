<?php
/**
 * Created by PhpStorm.
 * User: arief
 * Date: 20/02/18
 * Time: 21:53
 */

namespace App\Helpers;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;

class MyHelper {

    static function getUnikId($prefix, $number)
    {
        $id = $prefix."0001";
        if($number > 0)
        {
            if($number < 10)
            {
                $id = $prefix."000" . $number;
            }
            elseif($number > 9 && $number < 100)
            {
                $id = $prefix."00" . $number;
            }
            elseif($number > 99)
            {
                $id = $prefix."0" . $number;
            }
            elseif($number > 999)
            {
                $id = $prefix.$number;
            }
        }

        return $id;
    }

    static function getUnikId1($date, $prefix, $number)
    {
        $id = $prefix.date('Ym', strtotime($date))."-000001";
        if($number > 0)
        {
            if($number < 10)
            {
                $id = $prefix.date('Ym', strtotime($date))."-00000" . $number;
            }
            elseif($number > 9 && $number < 100)
            {
                $id = $prefix.date('Ym', strtotime($date))."-0000" . $number;
            }
            elseif($number > 99 && $number < 1000)
            {
                $id = $prefix.date('Ym', strtotime($date))."-000" . $number;
            }
            elseif($number > 999 && $number < 10000)
            {
                $id = $prefix.date('Ym', strtotime($date)).'-00'.$number;
            }
            elseif($number > 9999 && $number < 100000)
            {
                $id = $prefix.date('Ym', strtotime($date)).'-0'.$number;
            }
            elseif($number > 99999)
            {
                $id = $prefix.date('Ym', strtotime($date)).'-'.$number;
            }
        }

        return $id;
    }

    static function getUnikId2($prefix, $number, $endmark)
    {
        $id = $prefix."000001".$endmark;
        if($number > 0)
        {
            if($number < 10)
            {
                $id = $prefix."00000" . $number.$endmark;
            }
            elseif($number > 9 && $number < 100)
            {
                $id = $prefix."0000" . $number.$endmark;
            }
            elseif($number > 99 && $number < 1000)
            {
                $id = $prefix."000" . $number.$endmark;
            }
            elseif($number > 999 && $number < 10000)
            {
                $id = $prefix.'00'.$number.$endmark;
            }
            elseif($number > 9999 && $number < 100000)
            {
                $id = $prefix.'0'.$number.$endmark;
            }
            elseif($number > 99999)
            {
                $id = $prefix.$number.$endmark;
            }
        }

        return $id;
    }

    static function getHash($password)
    {
        $salt       = sha1(rand());
        $salt       = substr($salt, 0, 10);
        $encrypted  = password_hash($password.$salt, PASSWORD_DEFAULT);
        $hash       = array("salt" => $salt, "encrypted" => $encrypted);

        return $hash;
    }

    static function verifyHash($password, $dbPassword)
    {
        return password_verify($password, $dbPassword);
    }

    static function date_id($timestamp = '', $date_format = 'l, j F Y H:i', $suffix = ' WIB')
    {
        if (trim ($timestamp) == '')
        {
            $timestamp = time ();
        }
        elseif (!ctype_digit ($timestamp))
        {
            $timestamp = strtotime ($timestamp);
        }
        # remove S (st,nd,rd,th) there are no such things in indonesia :p
        $date_format = preg_replace ("/S/", "", $date_format);
        $pattern = array (
            '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
            '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
            '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
            '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
            '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
            '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
            '/April/','/June/','/July/','/August/','/September/','/October/',
            '/November/','/December/',
        );
        $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
            'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
            'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
            'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
            'Oktober','November','Desember',
        );
        $date = date ($date_format, $timestamp);
        $date = preg_replace ($pattern, $replace, $date);
        $date = "{$date}{$suffix}";
        return $date;
    }

    static function diff_day($date)
    {
        $today      = Carbon::today();
        $date_end   = Carbon::parse(date('Y-m-d', strtotime($date)));
        $diff       = $date_end->diffInDays($today);

        $diff_text  = "Sisa " . $diff . " Hari lagi";
        if($today == $date_end)
        {
            $diff_text  = "Berakhir hari ini";
        }

        return $diff_text;
    }

    static function diff_time($datetime1, $datetime2, $datetime3)
    {
        $date_time1 = Carbon::parse(date('H:i', strtotime($datetime1)));
        $date_time2 = Carbon::parse(date('H:i', strtotime($datetime2)));
        $date_time3 = Carbon::parse(date('H:i', strtotime($datetime3)));

        $diff       = $date_time2->diff($date_time1);

        $diff_text      = "Telat " . $diff->h . " jam " . $diff->i . " menit";
        if($date_time2 >= $date_time3 && $date_time2 <= $date_time1)
        {
            $diff_text  = "Tepat Waktu";
        }
        else if($diff->h == 0)
        {
            $diff_text      = "Telat " . $diff->i . " menit";
        }
        else if($diff->i == 0)
        {
            $diff_text      = "Telat " . $diff->h . " jam";
        }

        return $diff_text;
    }

    static function month_name($month)
    {
        $name = DateTime::createFromFormat('!m', $month);
        return self::date_id($name->format('M'), 'F', '');
    }

    static function month_name_year($month)
    {
        $name = DateTime::createFromFormat('!m', $month);
        return self::date_id($name->format('M'), 'F Y', '');
    }

    static function month_select()
    {
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
            $months[] = (object)array(
                'no'    => $i,
                'name'  => MyHelper::month_name($i)
            );
        }

        return $months;
    }

    static function date_range($start, $end, $format = 'Y-m-d')
    {
        try {
            // Declare an empty array
            $array = array();

            // Variable that store the date interval
            // of period 1 day
            $interval = new DateInterval('P1D');

            $realEnd = new DateTime($end);

            $realEnd->add($interval);

            $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

            // Use loop to store date into array
            foreach($period as $date) {
                $array[] = $date->format($format);
            }

            // Return the array elements
            return $array;

        } catch (\Exception $e) {
        }
    }   
}
