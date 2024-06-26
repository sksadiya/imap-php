<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Webklex\PHPIMAP\ClientManager;
class GmailController extends Controller
{
    public function index()
{
    try {
        Log::info('Attempting to connect to IMAP server');

        // Connect to the IMAP server
        $client = Client::account('default');
        $client->connect();
        Log::info('Connected to IMAP server successfully');

        // Fetch all folders
        $folders = $client->getFolders();
        Log::info('Folders fetched: ' . count($folders));
        $folderNames = $this->getAllFolderNames($folders);
        Log::info('Folder names: ' . implode(', ', $folderNames));
        $mailbox = [];

        // Set the date filter to 5 days ago
        $sinceDate = Carbon::now()->subDays(5);

        foreach ($folders as $folder) {
            $this->fetchFolderMessages($folder, $mailbox, $sinceDate);
        }

        Log::info('Mailbox array', $mailbox); // Log mailbox array

        $client->disconnect();
        Log::info('Disconnected from IMAP server');

        return view('gmail.index', ['mailbox' => $mailbox, 'folders' => $folders, 'folderNames' => $folderNames]);
    } catch (\Webklex\PHPIMAP\Exceptions\Exception $e) {
        Log::error('IMAP Error: ' . $e->getMessage());
        return view('gmail.error', ['error' => 'IMAP Error: ' . $e->getMessage()]);
    } catch (\Exception $e) {
        Log::error('General Error: ' . $e->getMessage());
        return view('gmail.error', ['error' => 'General Error: ' . $e->getMessage()]);
    }
}

private function fetchFolderMessages($folder, &$mailbox, $sinceDate)
{
    Log::info('Fetching messages from folder: ' . $folder->full_name);

    // Fetch messages since the given date
    if (strtoupper($folder->full_name) === 'INBOX') {
        // Fetch messages from the Inbox folder
        $messages = $folder->query()->since($sinceDate)->get();
    } else {
        // For other folders, fetch messages as usual
        $messages = $folder->query()->since($sinceDate)->get();
    }

    $emails = [];
    foreach ($messages as $message) {
        Log::info('Processing message: ' . $message->getMessageId());

        $emails[] = [
            'subject' => $message->getSubject(),
            'from' => $message->getFrom()[0]->mail ?? 'Unknown',
            'date' => Carbon::parse($message->getDate()),  // Convert to Carbon instance
            'body' => $message->getHTMLBody(),
        ];
    }

    $mailbox[$folder->full_name] = $emails;

    // Recursively fetch messages for child folders
    if ($folder->hasChildren()) {
        foreach ($folder->children as $child) {
            $this->fetchFolderMessages($child, $mailbox, $sinceDate);
        }
    }
}

private function getAllFolderNames($folders)
{
    $folderNames = [];
    foreach ($folders as $folder) {
        $folderNames[] = $folder->full_name;
        if ($folder->hasChildren()) {
            $folderNames = array_merge($folderNames, $this->getAllFolderNames($folder->children));
        }
    }
    return $folderNames;
}

public function testConnection()
{
    try {
        Log::info('Testing IMAP connection');

        // Initialize the client manager and create a client
        $clientManager = new ClientManager();
        $client = Client::account('default');

        // Connect to the IMAP server
        $client->connect();
        Log::info('Connected to IMAP server successfully');

        // Fetch folders
        $folders = $client->getFolders();
        $folderNames = $this->getAllFolderNames($folders);

        Log::info('Fetched folders: ' . implode(', ', $folderNames));
        dd($folderNames);

        // Disconnect from the IMAP server
        $client->disconnect();
        Log::info('Disconnected from IMAP server');

        return response()->json(['status' => 'success', 'message' => 'Connection tested successfully', 'folders' => $folderNames]);
    } catch (\Exception $e) {
        Log::error('IMAP Connection Test Error: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'Connection test failed: ' . $e->getMessage()]);
    }
}



}
