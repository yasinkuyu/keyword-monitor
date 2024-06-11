<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\User;
use App\Models\KeywordPosition;
use App\Models\Domain;
use App\Models\Keyword; 

class DemoData extends Seeder
{
    /**
     * Run the database seeds.
     * https://laravel.com/docs/11.x/seeding
     */
    public function run(): void
    {
        // Create and add your demo data here
         
        $user = User::create([
            'name' => 'Yasin',
            'email' => 'demo@example.com',
            'password' => Hash::make('demo')
        ]);

        $domain = Domain::create([
            'name' => 'domain.com',
            'user_id' => $user->id
        ]);

        $keywords = [
            'buy domain',
            'domain names for sale',
            'domain registration',
            'domain transfer',
            'domain valuation',
            'domain auction',
            'domain broker',
            'domain investing',
            'domain flipping',
            'premium domains',
        ];
        
        foreach ($keywords as $keyword) {

            $newKeyword = Keyword::create([
                'keyword' => $keyword,
                'domain_id' => $domain->id
            ]);
        }
        
        $keywordIds = Keyword::where('domain_id', $domain->id)->pluck('id')->toArray();
        $domainId = $domain->id;
        
        foreach ($keywordIds as $keywordId) {
            $keyword = Keyword::find($keywordId);
        
            if ($keyword) {
                $startDate = Carbon::now()->subDays(180);
                $endDate = Carbon::now();
                $languagesCountries = [
                    ['language' => 'tr', 'country' => 'tr'],
                    ['language' => 'en', 'country' => 'us']
                ];
        
                $data = [];
                $basePositionTr = 50;
                $basePositionEn = 50;
        
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    // Pozisyonu biraz arttır veya azalt ve rastgele bir dalgalanma ekle
                    $basePositionTr += rand(-1, 1) + rand(-2, 2);
                    $basePositionEn += rand(-1, 1) + rand(-2, 2);
        
                    // Pozisyonun 1 ile 100 arasında kalmasını sağla
                    $positionTr = min(max($basePositionTr, 1), 100);
                    $positionEn = min(max($basePositionEn, 1), 100);
        
                    foreach ($languagesCountries as $lc) {
                        $position = ($lc['language'] == 'tr') ? $positionTr : $positionEn;
                        $data[] = [
                            'keyword_id' => $keyword->id,
                            'domain_id' => $domainId,
                            'position' => $position,
                            'language' => $lc['language'],
                            'country' => $lc['country'],
                            'created_at' => $date->toDateTimeString(),
                            'updated_at' => $date->toDateTimeString(),
                        ];
                    }
                }
                DB::table('keyword_positions')->insert($data);
            }
        }

    }
}
