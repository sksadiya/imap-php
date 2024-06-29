<?php 
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
class GmailController extends Controller
{

    public function send(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'cc' => 'nullable|email',
            'bcc' => 'nullable|email',
        ]);

        $to = $request->input('to');
        $cc = $request->input('cc');
        $bcc = $request->input('bcc');
        $subject = $request->input('subject');
        $message = $request->input('message');

        Mail::raw($message, function ($mail) use ($to, $cc, $bcc, $subject) {
            $mail->to($to)
                 ->subject($subject);
            
            if ($cc) {
                $mail->cc($cc);
            }

            if ($bcc) {
                $mail->bcc($bcc);
            }
        });

        return redirect()->back()->with('success', 'Email sent successfully!');
    }


}
