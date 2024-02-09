<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegraphChat;
use Symfony\Component\Console\Command\Command as CommandAlias;

class PostToMusicLovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hunter:music
                                {--link=http://faketest.com.hu : Link to source}
                                {--comment=Comment : Comment about it}
                                {--tags=Tags : Tags}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
    /**
     * @var TelegraphChat
     */
    private TelegraphChat $tChat;
    
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->tChat = TelegraphChat::find(1);
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $link = $this->option('link');
        $comment = $this->option('comment');
        $tags = strin(',', $this->option('tags'));
        
        $this->tChat->html(implode("\n", $link))->send();
        
        return CommandAlias::SUCCESS;
    }
}
