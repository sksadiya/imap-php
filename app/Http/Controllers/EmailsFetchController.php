<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webklex\IMAP\Facades\Client;
use Carbon\Carbon;
use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Pagination\LengthAwarePaginator;
use Webklex\PHPIMAP\Support\MessageCollection;
class EmailsFetchController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = Client::account('default'); // Default account from the config
        $this->client->connect();
       
    }

    public function showEmailTabs(Request $request)
    {
        // Fetch folders
        $folders = $this->getFolders();
        
        // Determine the current page from the query string
        $page = $request->query('page', 1);
        
        // Prepare an array to store paginated messages for each folder
        $messages = [];
        
        foreach ($folders as $folder) {
            $folderName = $folder['name'];
            $perPage = 10; // Number of messages per page
            $folderMessages = $this->getFolderMessages($folderName, $page, $perPage);
            $messages[$folderName] = $folderMessages;
        }
        
        return view('gmail.emails', compact('folders', 'messages', 'page'));
    }
    
    public function getFolders()
    {
        $folders = $this->client->getFolders();
        $foldersArray = [];
        foreach ($folders as $folder) {
            $this->flattenFolder($folder, $foldersArray, $folder->name);
        }
        return $foldersArray;
    }
    
    private function flattenFolder($folder, &$foldersArray, $folderPath = '')
    {
        $foldersArray[] = [
            'name' => $folder->name,
            'folderpath' => $folderPath,
        ];
    
        foreach ($folder->children as $child) {
            $this->flattenFolder($child, $foldersArray, $folderPath.'/'.$child->name);
        }
    }
    
    public function getFolderMessages($folderName, $page = 1, $perPage = 10)
    {
        set_time_limit(300); // Set execution time to 5 minutes
        $folder = $this->client->getFolderByName($folderName);
    
        if (!$folder) {
            return response()->json(['error' => 'Folder not found'], 404);
        }
    
        $sinceDate = Carbon::now()->subDays(5);
        $messages = $folder->query()->since($sinceDate)->get();
    
        if (!($messages instanceof MessageCollection)) {
            return response()->json(['error' => 'Failed to fetch messages'], 500);
        }
    
        $totalMessages = $messages->count();
        $offset = ($page - 1) * $perPage;
        $pagedMessages = $messages->slice($offset, $perPage)->all();
    
        $messagesData = [];
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
    
        foreach ($pagedMessages as $message) {
            $subject = $message->getHeader()->get('subject')[0] ?? '(No Subject)';
            $messagesData[] = [
                'subject' => $subject,
                'from' => $message->getFrom()[0]->mail ?? 'Unknown',
                'date' => Carbon::parse($message->getDate()),
                'body' => $purifier->purify($message->getHtmlBody()),
                'folder' => $folder->name,
                'to' => env('IMAP_USERNAME'),
                'id' => $message->getUid(),
            ];
        }
    
        return new LengthAwarePaginator($messagesData, $totalMessages, $perPage, $page, ['path' => url()->current()]);
    }
    


}
