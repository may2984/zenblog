<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Instamojo;
use Instamojo\Instamojo as InstamojoInstamojo;
use Throwable;
use Razorpay\Api\Api;

class TestContoller extends Controller
{

    public function payRazorPay()
    {
        $key_id = env('RAZOR_PAY_CLIENT_ID');
        $secret = env('RAZOR_PAY_SECRET_KEY');

        $api = new Api($key_id, $secret);
        
        $response = $api->paymentLink->create(
            array(
                'amount' => 500, 
                'currency' => 'INR', 
                'accept_partial' => true,
                'first_min_partial_amount' => 100, 
                'description' => 'For XYZ purpose', 
                'customer' => array('name' => 'Ruchi Kaintura', 'email' => 'parmarmayank29@gmail.com', 'contact' => '8383804676'),  
                'notify' => array('sms' => true, 'email' => true),
                'reminder_enable' => true ,
                'notes' => array('policy_name' => 'Razor Pay Test'),
                'callback_url' => 'http://127.0.0.1:8000/razorpay/success',
                'callback_method' => 'get',
                'reference_id' => Str::random()
            ));  
            
        $short_url = $response['short_url'];

        return redirect($short_url);
    }

    public function RazorPaySuccess(Request $request)
    {
        dd(
            $request->get('razorpay_payment_id'),
            $request->get('razorpay_payment_link_id'),
            $request->get('razorpay_payment_link_status'),
            $request->get('razorpay_signature'),
            $request->get('razorpay_payment_link_reference_id'),
        );        

        # razorpay/success?razorpay_payment_id=pay_LuCCiX7DdXLkbd&razorpay_payment_link_id=plink_LuC9vg7kvHiMqW&razorpay_payment_link_reference_id=&razorpay_payment_link_status=paid&razorpay_signature=ba2dd3bdcee38bd45026b52ca1093d2066782f0141b4d55a5997c3c420717c17
    }

    public function index()
    {
               
        $output = config('app.name');

        return view('admin.test', [
            'output' => $output,
        ]);
    }

    public function payInstaMojo()
    {
        $authType = "app";
        
        $api = Instamojo\Instamojo::init($authType,[
            "client_id" =>  config('services.instamojo.client_id'),
            "client_secret" => config('services.instamojo.client_secret'),
        ],true); /** true for sandbox enviorment**/

        try {
            $response = $api->createPaymentRequest(array(
                "purpose" => "Shoping for Baby Ishu",
                "buyer_name" => "Mayank Singh Parmar",
                "amount" => "125.75",
                "send_email" => false,
                "phone" => 8383804676,
                "email" => "parmarmayank29@gmail.com",
                "redirect_url" => 'http://127.0.0.1:8000/instamojo/success'
                ));

            return redirect($response['longurl']);
        }
        catch (\Throwable $th) {
            print('Error: ' . $th->getMessage());
        }
        
    }

    public function instaMojoSuccess(Request $request)
    {       

          dd($request);

          # Failed Payment
          # requestUri: "/instamojo/success?payment_id=MOJO3526905A13502627&payment_status=Failed&payment_request_id=a4831e9c43c14f65ae0780359b58d5fe"

          # payment_id >> Credit | Failed
          # payment_status
          # payment_request_id
    }

    public function instaMojoRequestDetails()
    {
        $api = Instamojo\Instamojo::init('app',[
                "client_id" =>  config('services.instamojo.client_id'),
                "client_secret" => config('services.instamojo.client_secret'),
              ], true);

        try {
            # with >> payment_request_id
            $response = $api->getPaymentRequestDetails('a4831e9c43c14f65ae0780359b58d5fe');

            # with >> payment_id
            $response = $api->getPaymentDetails('MOJO3526905A13502627');

            dd($response);
        }
        catch (Throwable $e) {
            dd('Error: ' . $e->getMessage());
        }        
    }
    
    public function instaMojoCreateGatewayOrder()
    {
        $api = InstamojoInstamojo::init('app',[
            "client_id" =>  config('services.instamojo.client_id'),
            "client_secret" => config('services.instamojo.client_secret'),
        ], true);

        try {
            $response = $api->createGatewayOrder(array(
              "name" => "Mohit",
              "email" => "parmarmayank29@gmail.com",
              "phone" => "8383804676",
              "amount" => "200.45",
              "transaction_id" => 'TXN_ID', /**transaction_id is unique Id**/
              "currency" => "INR"
            ));
            print_r($response);
        }
        catch (Throwable $e) {
            print('Error: ' . $e->getMessage());
        }
        
    }
}
