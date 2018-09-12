<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Support\Collection;
use Validator;
use App\Mail\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class MailController extends Controller {

    /**
     * MailController constructor.
     */
    public function __construct()
    {
        $this->middleware('web');
    }


    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required'
        ]);
    }

    public function contact(Request $request) {

        //dd($request->all());
        $this->validator($request->all())->validate();

        $from = [ 'address' => env('MAIL_FROM_ADDRESS'), 'name' => env('MAIL_FROM_NAME') ];
        $to = new Collection; $to->push($from);

        $attr = [
            'name' => $request->name,
            'message' => $request->message,
            'infos' => [
                'telefono' => $request->phone,
                'mail' => $request->email
            ]
        ];

        Mail::send(new Contact($from, $to, 'layouts.mails.contact', $attr));
        return back()->with('success', 'Thanks for contacting us!');

    }

}