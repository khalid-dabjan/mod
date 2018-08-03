<?php

namespace App\Console\Commands;

use App\Events\ContestEvents;
use App\Model\Contest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ContestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contest:winner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Contest Winner';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contests = Contest::where('expired', 0)->where('expired_at', '<=', Carbon::now())->get();
        foreach ($contests as $contest) {
            $items = $contest->items()->orderBy('likes', 'DESC')->take(5)->get();
            $contest->winners()->sync($items->pluck('id')->toArray());
            $contest->expired=1;
            $contest->save();
            event(new ContestEvents($contest, $items));
        }
    }
}
