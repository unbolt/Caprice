<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\ItemUpdateRequest;

use App\Events\ItemUpdatedEvent;

class ItemController extends Controller
{

    /**
     * Get all the items
     *
     * @return void
     */
    public function listAllItems()
    {

        // Get all items from the items folder
        $files = Storage::files('items');

        foreach($files as $file) {
            $items[] = $this->returnItemData($file);
        }

        return response()->json($items);
    }

    /**
     * Save item data to the server
     *
     * @param Request $request
     * @return void
     */
    public function saveItemData(ItemUpdateRequest $request)
    {
        $item = array();
        $item['name'] = $request->input('item_name');
        $item['qty'] = $request->input('qty');

        $file_path = $this->pathFromItemName($request->input('item_name'));
        $file_name = $this->fileFromItemName($request->input('item_name'));

        // Save or update the file
        $this->writeItemData($file_path, $item);

        event(new ItemUpdatedEvent($file_name, $item['name'], $item['qty']));

        return response()->json('Updated');
    }

    public function retrieveItemData(Request $request, $item) {
        $file_name = $this->pathFromItemName($item);

        $item = $this->returnItemData($file_name);

        return response()->json($item);

    }

    /**
     * Sluggify the item name for a file name
     *
     * @param [type] $name
     * @return void
     */
    private function pathFromItemName($name)
    {
        return 'items/'. $this->fileFromItemName($name)  .'.txt';
    }

    private function fileFromItemName($name)
    {
        return Str::of($name)->slug('_');
    }


    private function writeItemData($path, $data)
    {
        return Storage::disk('local')->put($path, json_encode($data));
    }

    private function returnItemData($path)
    {
        $item = Storage::disk('local')->get($path);
        return json_decode($item);
    }
}
