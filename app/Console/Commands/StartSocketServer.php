<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\SocketEvent;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class StartSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the PHP WebSocket server';

    /**
     * Execute the console command.
     */
    public function _CC_handle()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new SocketEvent()
                )
            ),
            6002, // Your WebSocket port,
           ' 127.0.0.1'
        );
        // dd($server);

        $this->info("WebSocket server started on port 6002");
        $server->run();
    }


    public function handle()
    {
        $loop = \React\EventLoop\Factory::create();

        $webSocket = new WsServer(
            new SocketEvent()
        );

        $server = new IoServer(
            new HttpServer($webSocket),
            new \React\Socket\SocketServer('0.0.0.0:6002', [], $loop),
            $loop
        );

        $this->info("WebSocket server started on ws://0.0.0.0:6002");
        $server->run();
    }

}
