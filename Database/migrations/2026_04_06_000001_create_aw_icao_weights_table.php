<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aw_icao_weights', function (Blueprint $table) {
            $table->id();
            $table->string('icao', 10);
            $table->string('engine_type', 50)->nullable();
            $table->integer('dow')->nullable()->comment('Dry Operating Weight (kg)');
            $table->integer('mzfw')->nullable()->comment('Max Zero Fuel Weight (kg)');
            $table->integer('mtow')->nullable()->comment('Max Takeoff Weight (kg)');
            $table->integer('mlw')->nullable()->comment('Max Landing Weight (kg)');
            $table->text('source_url')->nullable();
            $table->text('note')->nullable();
            $table->boolean('fallback')->default(false);
            $table->timestamps();
        });

        // Seed all reference aircraft
        $data = [
            ['A306', 'GE/PW',            90000,  130000, 171700, 140000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A310', 'GE/PW',            80000,  114020, 164000, 123000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A318', 'CFM/PW',           39000,   54500,  68000,  57500, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A319', 'CFM/IAE',          40000,   58500,  75500,  62500, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A19N', 'CFM/PW',           42500,   64400,  75500,  67400, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A320', 'CFM/IAE',          42600,   64500,  78000,  66000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A20N', 'CFM/PW',           44600,   64300,  79000,  67400, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A321', 'CFM/IAE',          48900,   73500,  93500,  77800, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A21N', 'CFM/PW',           50200,   75800,  97000,  79600, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A332', 'GE/PW/RR',        116000,  168000, 230000, 180000, 'https://www.aircraft.airbus.com/sites/g/files/jlcbta126/files/2025-12/AC_A330_20251201.pdf'],
            ['A333', 'GE/PW/RR',        124500,  175000, 242000, 187000, 'https://www.aircraft.airbus.com/sites/g/files/jlcbta126/files/2025-12/AC_A330_20251201.pdf'],
            ['A338', 'RR',              129000,  181000, 251000, 191000, 'https://www.aircraft.airbus.com/sites/g/files/jlcbta126/files/2025-12/AC_A330_20251201.pdf'],
            ['A339', 'RR',              134000,  181000, 251000, 191000, 'https://www.aircraft.airbus.com/sites/g/files/jlcbta126/files/2025-12/AC_A330_20251201.pdf'],
            ['A343', 'CFM',             129000,  178000, 275000, 190000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A345', 'RR',              170400,  223000, 372000, 251000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A346', 'RR',              177000,  230000, 380000, 259000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A359', 'RR',              142400,  195700, 280000, 207000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A35K', 'RR',              156000,  223000, 319000, 236000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['A388', 'GE/RR',           277000,  369000, 575000, 395000, 'https://www.aircraft.airbus.com/en/customer-care/fleet-wide-care/airport-operations-and-aircraft-characteristics'],
            ['B712', 'RR',               31600,   46100,  54885,  47627, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B721', 'PW',               42500,   60200,  77000,  66600, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B722', 'PW',               46600,   65317,  95250,  68900, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B732', 'PW',               27800,   46000,  52390,  49580, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B733', 'CFM',              31480,   50030,  62820,  54660, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B734', 'CFM',              33030,   53070,  68040,  56290, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B735', 'CFM',              31310,   49170,  60550,  49900, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B736', 'CFM',              36500,   54660,  66000,  55480, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B737', 'CFM',              37650,   55202,  70080,  58604, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B738', 'CFM',              41413,   62732,  79015,  66361, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B739', 'CFM',              45070,   67721,  85139,  71350, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B37M', 'CFM',              40600,   58600,  80000,  67400, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B38M', 'CFM',              45100,   65952,  82190,  69308, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B39M', 'CFM',              47000,   67000,  88314,  71348, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B3XM', 'CFM',              50300,   69700,  89100,  74700, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B741', 'PW/RR/GE',        162400,  238000, 333400, 260800, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B742', 'PW/RR/GE',        173700,  248500, 377800, 285800, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B744', 'PW/RR/GE',        178756,  251290, 396890, 295742, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B748', 'GE',              220128,  293710, 447696, 306174, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B752', 'PW/RR',            57700,   84600, 115680,  95100, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B753', 'PW/RR',            63800,   95255, 123600, 101600, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B762', 'GE/PW/RR',         80280,  116500, 142880, 123376, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B763', 'GE/PW/RR',         86600,  123800, 186880, 145150, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B764', 'GE/PW',           103870,  145150, 204120, 158757, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B772', 'GE/PW/RR',        139380,  190510, 247210, 208650, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B77E', 'GE/PW/RR',        142880,  194140, 297550, 213190, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B77L', 'GE',              157930,  224530, 347450, 251290, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B773', 'GE/PW/RR',        160530,  224530, 299370, 237680, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B77W', 'GE',              167829,  237680, 351534, 251290, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B788', 'GEnx/RR',         119950,  161000, 227930, 172365, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B789', 'GEnx/RR',         128849,  181436, 254011, 192776, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['B78X', 'GEnx/RR',         135000,  192000, 254741, 202300, 'https://www.boeing.com/commercial/airport-tech/plan_manuals.page'],
            ['MD11', 'GE/PW',           128800,  181436, 286000, 199580, 'https://en.wikipedia.org/wiki/McDonnell_Douglas_MD-11'],
            ['DH8A', 'Turboprop',        10200,   14515,  15650,  15500, 'https://en.wikipedia.org/wiki/De_Havilland_Canada_Dash_8'],
            ['DH8B', 'Turboprop',        10800,   15195,  16329,  15877, 'https://en.wikipedia.org/wiki/De_Havilland_Canada_Dash_8'],
            ['DH8C', 'Turboprop',        11200,   17917,  19505,  18600, 'https://en.wikipedia.org/wiki/De_Havilland_Canada_Dash_8'],
            ['DH8D', 'Turboprop',        17100,   25200,  29257,  28009, 'https://en.wikipedia.org/wiki/De_Havilland_Canada_Dash_8'],
            ['B461', 'ALF',              24490,   29484,  38555,  34700, 'https://en.wikipedia.org/wiki/BAe_146'],
            ['B462', 'ALF',              24720,   32205,  42220,  36878, 'https://en.wikipedia.org/wiki/BAe_146'],
            ['B463', 'ALF',              24600,   36741,  44226,  39554, 'https://en.wikipedia.org/wiki/BAe_146'],
            ['ARJ1', 'LF',               25700,   35600,  38780,  35000, 'https://en.wikipedia.org/wiki/Avro_RJ'],
            ['ARJ2', 'LF',               26200,   38000,  44000,  38500, 'https://en.wikipedia.org/wiki/Avro_RJ'],
            ['ARJ3', 'LF',               27000,   40900,  44500,  39800, 'https://en.wikipedia.org/wiki/Avro_RJ'],
            ['CL30', 'HTF',              11250,   12338,  17622,  15309, 'https://en.wikipedia.org/wiki/Bombardier_Challenger_300'],
            ['CL35', 'HTF',              11340,    null,  18144,  16330, 'https://en.wikipedia.org/wiki/Bombardier_Challenger_350'],
            ['CL60', 'Turbofan',         12200,   14515,  20640,  19324, 'https://en.wikipedia.org/wiki/Bombardier_Challenger_600'],
            ['C25M', 'GE',               3171,    null,   4853,   4536, 'https://en.wikipedia.org/wiki/Cessna_CitationJet/M2'],
            ['C525', 'Williams',          3221,    3810,   5670,   5307, 'https://en.wikipedia.org/wiki/Cessna_CitationJet/M2'],
            ['C25A', 'Williams',          3642,    4400,   5670,   5228, 'https://www.easa.europa.eu/en/downloads/7368/en (TCDS EASA.IM.A.078, 525A0300 and on)'],
            ['C25B', 'Williams',          3874,    null,   6291,   5897, 'https://en.wikipedia.org/wiki/Cessna_CitationJet/M2'],
            ['C25C', 'Williams',          4663,    5670,   7761,   7203, 'https://en.wikipedia.org/wiki/Cessna_CitationJet/M2'],
            ['C56X', 'PW',               6058,    null,   9272,   8391, 'https://en.wikipedia.org/wiki/Cessna_Citation_Excel'],
            ['C680', 'PW',               7940,    9525,  13608,  12247, 'https://en.wikipedia.org/wiki/Cessna_Citation_Sovereign'],
            ['C68A', 'PW',               9312,    null,  13880,  12701, 'https://en.wikipedia.org/wiki/Cessna_Citation_Latitude'],
            ['C700', 'Honeywell',        10775,    null,  17817,  16329, 'https://en.wikipedia.org/wiki/Cessna_Citation_Longitude'],
            ['P28A', 'Piston',             680,    null,   1157,   1157, 'https://en.wikipedia.org/wiki/Piper_PA-28_Cherokee'],
            ['P28R', 'Piston',             794,    1247,   1247,   1247, 'https://en.wikipedia.org/wiki/Piper_PA-28_Cherokee'],
            ['PA32', 'Piston',            1043,    null,   1633,   1633, 'https://en.wikipedia.org/wiki/Piper_PA-32'],
            ['PA34', 'Piston',            1463,    2073,   2155,   2073, 'https://en.wikipedia.org/wiki/Piper_PA-34_Seneca'],
            ['PA31', 'Piston',            1919,    null,   2948,   2948, 'https://en.wikipedia.org/wiki/Piper_PA-31_Navajo'],
            ['P46T', 'Piston/Turboprop',  1450,    null,   2313,   2199, 'https://en.wikipedia.org/wiki/Piper_PA-46'],
            ['E135', 'AE',               12000,   17400,  19800,  18200, 'https://en.wikipedia.org/wiki/Embraer_ERJ_family'],
            ['E140', 'AE',               12150,   18500,  21000,  19000, 'https://en.wikipedia.org/wiki/Embraer_ERJ_family'],
            ['E145', 'AE',               12400,   18800,  22000,  19900, 'https://en.wikipedia.org/wiki/Embraer_ERJ_family'],
            ['E170', 'GE',               21157,   30900,  38600,  33300, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_family'],
            ['E75L', 'GE',               21906,   32000,  40370,  34100, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_family'],
            ['E190', 'GE',               27900,   40900,  51800,  44000, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_family'],
            ['E195', 'GE',               28700,   42600,  52290,  45800, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_family'],
            ['E290', 'PW',               33000,   46500,  56400,  49050, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_E2_family'],
            ['E295', 'PW',               35700,   51850,  62500,  54000, 'https://en.wikipedia.org/wiki/Embraer_E-Jet_E2_family'],
            ['A109', 'Turboshaft',        1750,    3200,   3200,   3200, 'https://en.wikipedia.org/wiki/AgustaWestland_AW109'],
            ['A139', 'Turboshaft',        3620,    5000,   6800,   6400, 'https://en.wikipedia.org/wiki/AgustaWestland_AW139'],
            ['C404', 'GTSIO-520-M',       2183,    3674,   3810,   3674, 'https://drs.faa.gov/browse/TCDSModel (TCDS A25CE)'],
            ['P68',  'IO-360-A1B6',       1200,    1860,   1960,   1860, 'https://www.easa.europa.eu/en/document-library/type-certificates (TCDS EASA.A.385)'],
        ];

        $now = now();
        foreach ($data as [$icao, $engine, $dow, $mzfw, $mtow, $mlw, $source]) {
            DB::table('aw_icao_weights')->insert([
                'icao'        => $icao,
                'engine_type' => $engine,
                'dow'         => $dow,
                'mzfw'        => $mzfw,
                'mtow'        => $mtow,
                'mlw'         => $mlw,
                'source_url'  => $source,
                'fallback'    => false,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('aw_icao_weights');
    }
};
