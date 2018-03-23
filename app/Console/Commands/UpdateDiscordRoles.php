<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LaravelRestcord\Discord;
use RestCord\DiscordClient;

class UpdateDiscordRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DiscordClient $discord)
    {
        $this->discord = $discord;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collective = file_get_contents('https://opencollective.com/radarr/members/users.json');
        $donators = json_decode($collective, true);
        $d_users = [];
        $regex = "/^.*?\s(?P<name>.+?#\d{4}).*?$/";
        foreach ($donators as $donator) {
            preg_match($regex, $donator['description'], $matches);
            if ($matches != null) {
                $d_users[$matches['name']] = $donator['role'];
            } else {
                $this->error('Could not find discord user name for backer: '.$donator['name']);
            }
        }

        $this->info('Found donators: '.(string) count($d_users));

        $guilds = [];
        $members = $this->discord->guild->listGuildMembers(['guild.id' => 264387956343570434, 'limit' => 1000]);
        $guilds = $members;
        while (count($members) == 1000) {
            $members = $this->discord->guild->listGuildMembers(['guild.id' => 264387956343570434, 'limit' => 1000, 'after' => $members[999]->user->id]);
            $guilds = array_merge($guilds, $members);
        }

        $g_roles = $this->discord->guild->getGuildRoles(['guild.id' => 264387956343570434]);

        foreach ($guilds as $user) {
            $username = $user->user->username.'#'.$user->user->discriminator;
            if (isset($d_users[$username])) {
                $type = $d_users[$username];
                $roles = $user->roles;
                $role = $type == 'BACKER' ? 425290590050058242 : 425290634350559232;
                if (!in_array($role, $roles)) {
                    $roles[] = $role;

                    $res = $this->discord->guild->modifyGuildMember(['guild.id' => 264387956343570434, 'user.id' => $user->user->id, 'roles' => $roles]);
                }

                unset($d_users[$username]);
            } else {
                //$this->error("No discord user found with: ".$username);
            }
        }

        foreach ($d_users as $name=>$type) {
            $this->error("Couldn't find discord user: ".$name);
        }
    }
}
