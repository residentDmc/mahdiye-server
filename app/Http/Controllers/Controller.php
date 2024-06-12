<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function getReport($collection, $reportType)
    {
        switch ($reportType) {
            case 'appointments':
                return $this->getReportOfAppointmnets($collection);
                break;

            case 'users':
                return $this->getReportOfUsers($collection);
                break;

            case 'reserves':
                return $this->getReportOfReserves($collection);
                break;

            default:
                # code...
                break;
        }

    }

    private function getReportOfAppointmnets($collection)
    {
        $csvFileName = 'خروجی اکسل نوبت ها.csv';
        $csvFileHeaders = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            'Pragma' => 'no-cache',
            'Expires' => 0,
        ];

        $callback = function () use ($collection) {
            $resource = fopen('php://output', 'w');
            fprintf($resource, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($resource, ['نام و نام خانوادگی', 'موبایل', 'کد ملی', 'تاریخ', 'زمان', 'نوبت', 'وضعیت']);
            foreach ($collection as $item) {
                fputcsv($resource, [
                    $item->user->first_name . " " . $item->user->last_name,
                    $item->user->mobile,
                    $item->user->national_code,
                    \Morilog\Jalali\Jalalian::forge($item->reserve->date)->format('Y/m/d'),
                    date("H:i", strtotime($item->reserve->start_time)) . ' تا ' . date("H:i", strtotime($item->reserve->end_time)),
                    $item->position,
                    $item->status_fa
                ]);
            }
            fclose($resource);
        };
        return response()->stream($callback, 200, $csvFileHeaders);
    }

    private function getReportOfUsers($collection)
    {
        $csvFileName = 'خروجی اکسل کاربران.csv';
        $csvFileHeaders = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            'Pragma' => 'no-cache',
            'Expires' => 0,
        ];

        $callback = function () use ($collection) {
            $resource = fopen('php://output', 'w');
            fprintf($resource, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($resource, ['نام و نام خانوادگی', 'نام پدر', 'موبایل', 'کد ملی', 'شماره شناسنامه','تاریخ تولد', 'تاریخ ثبت نام', 'نقش', 'وضعیت']);
            foreach ($collection as $user) {
                fputcsv($resource, [
                    $user->first_name . " " . $user->last_name,
                    $user->father_name,
                    $user->mobile,
                    $user->national_code,
                    $user->certificate_number,
                    \Morilog\Jalali\Jalalian::forge($user->birthdate)->format('Y/m/d'),
                    \Morilog\Jalali\Jalalian::forge($user->created_at)->format('Y/m/d'),
                    match ($user->role) {'admin' => 'ادمین', 'user' => 'کاربر'},
                    match ($user->status) {'active' => 'فعال', 'inactive' => 'غیر فعال'},
                ]);
            }
            fclose($resource);
        };
        return response()->stream($callback, 200, $csvFileHeaders);
    }

    private function getReportOfReserves($collection)
    {
        $csvFileName = 'خروجی اکسل رزرو ها.csv';
        $csvFileHeaders = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
            'Pragma' => 'no-cache',
            'Expires' => 0,
        ];

        $callback = function () use ($collection) {
            $resource = fopen('php://output', 'w');
            fprintf($resource, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($resource, ['تاریخ', 'ساعت شروع', 'ساعت پایان', 'ظرفیت کل', 'نوبت ها', 'وضعیت']);
            foreach ($collection as $reserve) {
                fputcsv($resource, [
                    \Morilog\Jalali\Jalalian::forge($reserve->date)->format('Y/m/d'),
                    \Carbon\Carbon::createFromFormat('H:i:s', $reserve->start_time)->format('H:i'),
                    \Carbon\Carbon::createFromFormat('H:i:s', $reserve->end_time)->format('H:i'),
                    $reserve->capacity . " نفر",
                    $reserve->used . " نفر",
                    match ($reserve->status) {'active' => 'فعال', 'inactive' => 'غیر فعال'},
                ]);
            }
            fclose($resource);
        };
        return response()->stream($callback, 200, $csvFileHeaders);
    }
}
