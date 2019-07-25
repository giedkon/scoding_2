<?php

namespace App\Console\Commands;

use App\location;
use GuzzleHttp\Client;
use App\warning_email;
use App\Mail\warning;
use App\Mail\warning_over;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class UpdateWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateWeather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all the location models with latest OWM info';

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

        $locations = location::all();
        $client = new Client();

        // Atnaujiname visų išsaugotų vietų duomenys, ir jeigu prireikia išsiunčiame įspėjima dėl vėjo greičio
        foreach ($locations as $location) {

            $res = $client->get('http://api.openweathermap.org/data/2.5/weather',
                ['query' => [
                    'id' => $location->OWM_ID,
                    'units' => 'metric',
                    'appid' => '886705b4c1182eb1c69f28eb8c520e20'
                ]]);

            $previousSpeed = $location->latest_speed;

            $locInfo = json_decode($res->getBody());
            $location->latest_temp = $locInfo->main->temp;
            $location->latest_speed = $locInfo->main->speed;
            $location->latest_direction = $locInfo->wind->deg;

            // Tikriname ar pasikeitė vėjo greitis, ir jeigu taip išsiunčiame paštą.
            if($previousSpeed < 10 && $location->latest_speed > 10){
                $location->warning = 1;

                $warning_emails = warning_email::all()->where('location', $location->OWM_ID);
                foreach ($warning_emails as $warning) {
                    Mail::to($warning->email)
                        ->send(new warning($location->name));
                    $this->line('Sent out a warning to' . $warning->email);
                }

            } else if ($previousSpeed > 10 && $location->latest_speed < 10) {
                $location->warning = 0;

                $warning_emails = warning_email::all()->where('location', $location->OWM_ID);
                foreach ($warning_emails as $warning) {
                    Mail::to($warning->email)
                        ->send(new warning_over($location->name));
                    $this->line('Sent out a warning over to' . $warning->email);
                }
                $this->line($location->name . ' updated');
            }

            $location->save();
            $this->line($location->name . ' updated');
        }
        $this->line('Seems like it worked');
    }
}
