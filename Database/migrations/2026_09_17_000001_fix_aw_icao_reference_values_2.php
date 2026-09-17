<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Zweite Korrektur der ausgelieferten ICAO-Referenztabelle (17.09.2026).
 *
 * Gefunden bei einem vollen Abgleich der Startwerte gegen Zulassungsunterlagen:
 *
 *  - A388: 575 t MTOW mit 394 t MLW und 361 t MZFW ist keine Airbus-Gewichtsvariante.
 *    Richtig ist WV011 = 575 / 395 / 369 t (Airbus A380 Aircraft Characteristics, 2-1-1).
 *  - B739: 146.607 / 187.699 / 152.696 lb passte zu keinem Boeing-Modell. Jetzt die
 *    737-900ER nach FAA TCDS A16WE: MZFW 149.300 lb, MLW 157.300 lb.
 *  - B737: 126.001 / 155.007 lb war keine echte 737-700-Kombination. Jetzt die
 *    Standard-Hochgewichtsoption 121.700 / 154.500 / 129.200 lb.
 *  - CL30: Challenger 300 mit 27.200 / 38.850 / 33.750 lb, MZFW fehlte ganz.
 *  - C25A: CJ2+ nach EASA TCDS IM.A.078 (525A0300 and on), MZFW fehlte, MLW war der CJ1-Wert.
 *  - A109, C25C, C525, C680, CL60, P28R, PA34: MZFW fehlte (Leichtflugzeuge und
 *    Hubschrauber: MZFW = MLW).
 *  - C404 und P68 neu (FAA TCDS A25CE, EASA TCDS A.385).
 *
 * WICHTIG — anders als die erste Korrektur prueft diese Migration die GANZE Zeile:
 * geaendert wird nur, wenn ALLE alten Grenzgewichte (MZFW/MTOW/MLW) eines Musters
 * noch genau so dastehen.
 * Wer auch nur einen Wert selbst angepasst hat, hat die Zeile bewusst in der Hand
 * (GSG selbst traegt z. B. bei B737 eine eigene, abweichende Wahl) — die bleibt
 * unangetastet. Ein fehlendes MZFW wird nur ergaenzt, wenn das Feld leer ist; ein
 * neues Muster nur angelegt, wenn es noch keine Zeile gibt.
 *
 * Die Gewichte der FLUGZEUGE aendert diese Migration nicht — dafuer ist "Sync" da.
 */
return new class extends Migration
{
    /** @var array<string, array{alt: array<string,int|null>, neu: array<string,int>}> */
    private array $zeilen = [
        'A388' => ['alt' => ['mzfw' => 361000, 'mtow' => 575000, 'mlw' => 394000], 'neu' => ['mzfw' => 369000, 'mlw' => 395000]],
        'B739' => ['alt' => ['mzfw' => 66500, 'mtow' => 85139, 'mlw' => 69262],    'neu' => ['mzfw' => 67721, 'mlw' => 71350]],
        'B737' => ['alt' => ['mzfw' => 57153, 'mtow' => 70310, 'mlw' => 58060],    'neu' => ['mzfw' => 55202, 'mtow' => 70080, 'mlw' => 58604]],
        'CL30' => ['alt' => ['mzfw' => null, 'mtow' => 17463, 'mlw' => 15660],     'neu' => ['mzfw' => 12338, 'mtow' => 17622, 'mlw' => 15309]],
        'C25A' => ['alt' => ['mzfw' => null, 'mtow' => 5670, 'mlw' => 5307],       'neu' => ['mzfw' => 4400, 'mlw' => 5228]],
    ];

    /** @var array<string,int> ICAO => MZFW, nur wenn das Feld leer ist */
    private array $mzfwFehlt = [
        'A109' => 3200,
        'C25C' => 5670,
        'C525' => 3810,
        'C680' => 9525,
        'CL60' => 14515,
        'P28R' => 1247,
        'PA34' => 2073,
    ];

    /** @var list<array{0:string,1:string,2:int,3:int,4:int,5:int,6:string}> */
    private array $neueMuster = [
        ['C404', 'GTSIO-520-M', 2183, 3674, 3810, 3674, 'https://drs.faa.gov/browse/TCDSModel (TCDS A25CE)'],
        ['P68',  'IO-360-A1B6', 1200, 1860, 1960, 1860, 'https://www.easa.europa.eu/en/document-library/type-certificates (TCDS EASA.A.385)'],
    ];

    public function up(): void
    {
        if (!Schema::hasTable('aw_icao_weights')) {
            return;
        }

        foreach ($this->zeilen as $icao => $fix) {
            $this->ersetzeWennUnveraendert($icao, $fix['alt'], $fix['neu']);
        }

        foreach ($this->mzfwFehlt as $icao => $mzfw) {
            DB::table('aw_icao_weights')->where('icao', $icao)->whereNull('mzfw')
                ->update(['mzfw' => $mzfw, 'updated_at' => now()]);
        }

        foreach ($this->neueMuster as [$icao, $engine, $dow, $mzfw, $mtow, $mlw, $quelle]) {
            if (DB::table('aw_icao_weights')->where('icao', $icao)->exists()) {
                continue;
            }
            DB::table('aw_icao_weights')->insert([
                'icao' => $icao, 'engine_type' => $engine, 'dow' => $dow, 'mzfw' => $mzfw,
                'mtow' => $mtow, 'mlw' => $mlw, 'source_url' => $quelle, 'fallback' => false,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('aw_icao_weights')) {
            return;
        }

        foreach ($this->zeilen as $icao => $fix) {
            $neuVoll = array_merge($fix['alt'], $fix['neu']);
            $this->ersetzeWennUnveraendert($icao, $neuVoll, $fix['alt']);
        }

        foreach ($this->mzfwFehlt as $icao => $mzfw) {
            DB::table('aw_icao_weights')->where('icao', $icao)->where('mzfw', $mzfw)
                ->update(['mzfw' => null, 'updated_at' => now()]);
        }

        foreach ($this->neueMuster as [$icao, , $dow, $mzfw, $mtow, $mlw]) {
            DB::table('aw_icao_weights')->where(compact('icao', 'dow', 'mzfw', 'mtow', 'mlw'))->delete();
        }
    }

    /**
     * @param array<string,int|null> $erwartet
     * @param array<string,int|null> $neu
     */
    private function ersetzeWennUnveraendert(string $icao, array $erwartet, array $neu): void
    {
        $q = DB::table('aw_icao_weights')->where('icao', $icao);
        foreach ($erwartet as $feld => $wert) {
            $wert === null ? $q->whereNull($feld) : $q->where($feld, $wert);
        }
        $q->update($neu + ['updated_at' => now()]);
    }
};
