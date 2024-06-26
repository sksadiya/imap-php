<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Carbon\Carbon;
use HTMLPurifier;
use HTMLPurifier_Config;
class EmailsFetchController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = Client::account('default'); // Default account from the config
        $this->client->connect();
       
    }

    public function showEmailTabs()
{
    // Assuming $this->getFolders() returns an array of folders
    $folders = $this->getFolders();

    // Prepare an array to store messages for each folder
    $messages = [];

    foreach ($folders as $folder) {
        $folderName = $folder['name'];

        // Fetch messages for the current folder
        $folderMessages = $this->getFolderMessages($folderName);
        // Store messages in the messages array
        $messages[$folderName] = $folderMessages;
    }
    return view('gmail.emails', compact('folders', 'messages'));
}

    public function getFolders()
{
    $folders = $this->client->getFolders();
    $foldersArray = [];

    foreach ($folders as $folder) {
        $this->flattenFolder($folder, $foldersArray);
    }

    return $foldersArray;
}

private function flattenFolder($folder, &$foldersArray)
{
    $foldersArray[] = [
        'name' => $folder->name,
    ];

    foreach ($folder->children as $child) {
        $this->flattenFolder($child, $foldersArray);
    }
}
    // public function getFolders()
    // {
    //     $folders = $this->client->getFolders();
    //     $data = $this->getFolderStructure($folders);

    //     return response()->json($data);
    // }

    // private function getFolderStructure($folders)
    // {
    //     $data = [];

    //     foreach ($folders as $folder) {
    //         $folderData = [
    //             'name' => $folder->name,
    //             'children' => $this->getFolderStructure($folder->children)
    //         ];
    //         $data[] = $folderData;
    //     }

    //     return $data;
    // }

    public function getFolderMessages($folderName)
    {
        set_time_limit(300); // Set execution time to 5 minutes
        $folder = $this->client->getFolderByName($folderName);

        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }
        $sinceDate = Carbon::now()->subDays(5);
        $messages = $folder->query()->since($sinceDate)->get();
        // $messages = $folder->messages()->all()->sortBy('date', 'desc')->limit(10)->get();  // Limit to 10 mes
        $messagesData = [];
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        foreach ($messages as $message) {
            $subject = $message->getHeader()->get('subject')[0] ?? '(No Subject)';
            $messagesData[] = [
            'subject' => $subject,
            'from' => $message->getFrom()[0]->mail ?? 'Unknown',
            'date' => Carbon::parse($message->getDate()),  // Convert to Carbon instance,
           'body' => $purifier->purify($message->getHtmlBody()),
           'folder' => $folder->name,  
           'to' => env('IMAP_USERNAME'),
           'id' =>$message->getUid(),
            ];
        }

        return $messagesData;
    }
}
