<?php

namespace App\Http\Controllers;
use Webklex\IMAP\Facades\Client;
use Illuminate\Http\Request;

class mailActions extends Controller
{
    public function handleEmailAction(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'action' => 'required|string',
            'message_id' => 'required|string',
            'folder' => 'required|string'
        ]);

        // Connect to the IMAP server
        $client = Client::account('default');
        $client->connect();

        // Get the folder where the email is located
        $folder = $client->getFolder($request->folder);

        // Get the message based on its ID
        $message = $folder->query()->getMessageByUid($request->message_id);

        switch ($request->action) {
            case 'archive':
                // Move the email to the "All Mail" folder
                $allMailFolder = "[Gmail]/All Mail";
                $message->move($allMailFolder);
                break;
            case 'delete':
                $trashFolder = "[Gmail]/Bin"; // Adjust folder name as per your configuration
                    $message->move($trashFolder);
                break;
            case 'markAsRead':
                // Mark the email as read
                $message->setFlag('Seen');
                break;
            case 'moveToSpam':
                // Move the email to the Spam folder
                $spamFolder = "[Gmail]/Spam";
                $message->move($spamFolder);
                break;
            case 'deleteForever':
                $message->delete();
                break;
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }

        return response()->json(['success' => true]);
    }

}
