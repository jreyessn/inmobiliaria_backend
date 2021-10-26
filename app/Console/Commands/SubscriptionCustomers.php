<?php

namespace App\Console\Commands;

use App\Models\Customer\CustomerSubscription;
use App\Notifications\Coupons\AutomaticFailedPurchaseCoupon;
use App\Notifications\Coupons\AutomaticPurchaseCoupon;
use App\Repositories\Coupons\CouponsMovementsRepositoryEloquent;
use App\Rules\CustomerCouponsAvailables;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class SubscriptionCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta las suscripciones automaticas de los clientes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        CouponsMovementsRepositoryEloquent $couponsMovementsRepository
    )
    {
        parent::__construct();
        $this->couponsMovementsRepository = $couponsMovementsRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $subscriptions = CustomerSubscription::all();

        foreach ($subscriptions as $subscription) {
            $dayPay    = new Carbon($subscription->next_pay_date);
            $current  = Carbon::now()->today();

            if($current->gte($dayPay)){
                $subscription->last_pay_date = Carbon::now();
                $subscription->next_pay_date = $dayPay->addDays($subscription->every_day);

                // validar
                $validator = Validator::make(["id" => $subscription->customer_id], [
                    "id" => [ new CustomerCouponsAvailables($subscription->customer_id, NULL) ]
                ]);
        
                if($validator->fails()){

                    Notification::route("mail", $subscription->customer->email)->notify(
                        new AutomaticFailedPurchaseCoupon([
                            "tradename"      => $subscription->customer->tradename,
                            "quantity"       => $subscription->quantity_coupons,
                        ])
                    );

                    return dump("Enviar correo de que no se ha realizado la venta");
                }

                // movimiento cupon
                $couponMovement = $this->saveAutomatic([
                    "customer_id"   => $subscription->customer_id,
                    "type_movement" => getMovement(1),
                    "quantity"      => $subscription->quantity_coupons,
                    "is_automatic"  => 1
                ]);

                if($subscription->customer->email){
                    Notification::route("mail", $subscription->customer->email)->notify(
                        new AutomaticPurchaseCoupon([
                            "folio"          => $couponMovement->folio,
                            "quantity"       => $couponMovement->quantity,
                            "quantity_total" => $couponMovement->customer->coupons,
                            "total"          => $couponMovement->total,
                            "encrypt_id"     => $couponMovement->customer->encrypt_id,
                            "next_pay_date"  => $subscription->next_pay_date
                        ])
                    );
                }
                
                $subscription->save();
            }

        }

        return 0;
    }

    public function saveAutomatic(array $data)
    {
        return $this->couponsMovementsRepository->save($data);
    }
}
