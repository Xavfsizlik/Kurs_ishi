<?php

namespace Database\Seeders;

use App\Models\Prime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Prime::create(['user_id' => 1 , 'prime' => '62929451391946289883789195934057704795467358738479288313111107839373289015112078971408166203694500301838401138432187026552746002083194897622573395058001531239176663767608261660869440793507952799477851875063385100826601472142638296468978624206882180872946426164927744967120251271427697053383787456063404744371694887789793491008462225966699488695862161824712162890410034423820014314860834398284993794464495644341813345508320906316967525371018504460596482754376844720652989362450274476800765316045383643219775969623431502749338027812668576480431373144067517490387862422330107750337835333883865437739027123015600949290513450430221769305279925401612692708795401460051209818544761772462372071574376043305674451639373844968302145763265247819446288020754315386222172212770229100047623555467683990971576581898552199187076099015830861176779449837222310675090496967524341311007503323177724616923159585654917571881175586393566123877134821914061978863681568188951554833926312471595440168171570965952841299063594887521954136342250776233206272140728949757550490678170912333037666445990281512338506569681260966792983716451501912560901889874261469229471572272547933586310517119253597880295585399330221990062788984598125420353332642483026136722999971']);
    }
}
