<?php

namespace App\Console\Commands;

use App\Models\Farm\Farm;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class DailyGetFarms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farms:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene las granjas de la API de Norson';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjdkZTg3ZGM4YTlmMjFhNTQxN2MzZTljOTRjMDUzZjdhMTJmNWE1ZDVmMTY3ZGNmNTc0OWZhNThmYjMwMWNiNWI5ZWE0OTkxNDAzZjg3MjVmIn0.eyJhdWQiOiIxIiwianRpIjoiN2RlODdkYzhhOWYyMWE1NDE3YzNlOWM5NGMwNTNmN2ExMmY1YTVkNWYxNjdkY2Y1NzQ5ZmE1OGZiMzAxY2I1YjllYTQ5OTE0MDNmODcyNWYiLCJpYXQiOjE2MDkzNjI0MTIsIm5iZiI6MTYwOTM2MjQxMiwiZXhwIjoxNjQwODk4NDEyLCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.E_snqrpwft2yPp-oEunEnQPVUB2LusgjHmHKNGVT17YmJq54imk-vJ147UdSS7k88fIi90oqw_nFhTCdn9ELzZy3mNtRya1ridbrvV_IDFcEf9cdzX9rY1HaHy1wSOZZ0TzgiQrzEcyzXZa6UYza39i9rrlrHFcpde9c2el1hUcEZIi_-vgDsDPreQVC-WyfqQQLhGVg-n-c24tOZ6KbSZqnesNREzWfI3-jcfyPtgai5GCXLKrA7lyFjNrU8aqW1mcbPMRs6zeVowBYqS7Fqm73CcxGHBA8QxKuUR5S2fgmFGuonRn_5HBh1c3mIf1ge4CrVoQNxfI1pZ3U9tyR8E1QNw2q2pvV-2zsLU3SSF771rgMW6zCn-WRmYrqrjRn5WgtZWd2WEnJQSPg7O1HusWCIwihV32a8xfXMNtNcp8rdYtwWB7_ElU1xNt-tcJu9LaDq-pZ7KmKXb906D-suPW_d4174GV0evUMncRfYpIG948UQ_BHclDDxxsnG3ak5G0jH5x12iRY9BDV2FBhaTOiBQOXbF62DXg6G3aorrZOYgDWmyj-JP8mhM4ILtnubNR7-aXF5CNTjasZshAmh3jRevSgmVy8G6Cv8U74p6DQAifEyf8-UX14aggB2AQxmle1fMpZ9MTLCCnqdW5LlACm0KiAlqzhRcirDByhTsc";
 
 
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
        $client = new Client();
        $response = $client->request('GET', 'http://201.147.196.67:1234/api/Centros/CENG', [
            'Authorization' => $this->token
        ]);

        $data = json_decode($response->getBody(), true);

        foreach ($data as $farm) {

            $farmFound = Farm::where('centro', $farm['Centro'])->first();
            
            if(is_null($farmFound)){
                Farm::create([
                    "centro" => $farm['Centro'],
                    "supervisor" => $farm['Supervisor'],
                    "gerente" => $farm['Gerente'],
                    "nombre_centro" => $farm['NombreCentro'],
                    "nombre_supervisor" => $farm['NombreSupervisor'],
                    "nombre_gerente" => $farm['NombreGerente'],
                ]);
            }
        }
    }
}
