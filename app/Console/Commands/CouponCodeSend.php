<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserInfo;
use App\Models\CouponCode;
use Carbon\Carbon;
use Mail;
use App\Mail\CouponMailSend;
use App\Console\Commands\Exception;

class CouponCodeSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'coupon send';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $user = UserInfo::latest()->get();
        
        if (count($user) > 0) {
            foreach ($user as $item) {
                $coupon = CouponCode::where('useremail', $item->email)->first();
               
                $userTime = Carbon::parse($coupon->created_at, 'UTC');
                $FinduserTime = $userTime->isoFormat('m');
                $FinduserMonth = $userTime->isoFormat('m');
                $systemTime = Carbon::parse(Carbon::now(), 'UTC');
                $FindsystemTime = $systemTime->isoFormat('m');
                $FindsystemMonth = $systemTime->isoFormat('M');
                $data = ['coupon_Code' => $coupon['couponCode']];
                // $this->info($FinduserTime);
                if ($FinduserTime === $FindsystemTime &&  $FinduserMonth === $FindsystemMonth ) {

                    $data = ['coupon_Code' => $coupon['couponCode']];
                    Mail::to($item->email)->send(new CouponMailSend($data));
                }
            }
        }
    }
}
