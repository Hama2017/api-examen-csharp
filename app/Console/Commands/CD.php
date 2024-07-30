<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class CD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'git:change-commit-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the dates of all commits in the Git repository';

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
     * @return int
     */
    public function handle()
    {
        // Configuration
        $startDate = Carbon::create(2020, 1, 1, 0, 0, 0);  // Date de début souhaitée pour le premier commit
        $increment = 1;  // Incrément de temps entre chaque commit (en jours)

        // Liste des commits
        $commits = explode("\n", trim(shell_exec('git rev-list --reverse HEAD')));

        // Application des nouvelles dates
        foreach ($commits as $i => $commit) {
            $newDate = $startDate->copy()->addDays($i);
            $newDateStr = $newDate->format('Y-m-d\TH:i:s');

            putenv('GIT_COMMITTER_DATE=' . $newDateStr);
            putenv('GIT_AUTHOR_DATE=' . $newDateStr);

            shell_exec('git filter-repo --force --commit-callback \'if commit.original_id == b"' . $commit . '": commit.committer_date = ' . $newDate->timestamp . '; commit.author_date = ' . $newDate->timestamp . ';\'');
        }

        $this->info('Commit dates have been changed successfully.');

        return 0;
    }
}
