<?php

namespace App\Console\Commands;

use App\Models\UploadImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Every Stored File from server every one month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pb_files = UploadImage::where('public',1)->get();

        if($pb_files)
        {
            foreach($pb_files as $item)
            {
                if(Storage::exists('public/'.$item->file))
                {
                    Storage::delete('public/'.$item->file);
                }
                $item->update([
                    'public'=> 0
                ]);
            }
        }
    }
}
