<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyCheckCompanyExpireLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companylicensecheck:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check company license expire.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        $date = new \DateTime(date("Y-m-d"));
        $date->modify('+7 day');
        $company_license = \App\Models\CompanyLicense::select('id', 'company_id')
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->whereDate('end_date', '<=', $date->format('Y-m-d'))
            ->get();
        foreach ($company_license as $key => $value) {
            $staffList = \App\Models\Staff::select('id')
                ->where('company_id', $value->company_id)
                ->where('role', config('constants.COMPANY_ADMIN'))
                ->get();
            $messageInfo = [
                'body' => 'COMPANY LICENSE EXPIRE SOON!',
                'type' => config('constants.NOTIFICATION_COMPANY_LICENSE_EXPIRE'),
            ];
            foreach ($staffList as $key => $value) {
                $staff = \App\Models\Staff::find($value->id);
                $staff->notify(new \App\Notifications\AdminNotification($messageInfo));
            }
        }

        $this->info('Successfully check company license expire.');
    }
}
