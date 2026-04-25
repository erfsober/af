<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateMonthlyChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->charges() as $charge) {
            $tenant = Tenant::query()
                ->find((int) ($charge['plaque']));
            if ($tenant) {
                $tenant->monthly_charge_amount = $charge['monthly_charge'];
                $tenant->save();
            }
        }
    }

    public function charges()
    {
        return [
            [
                'plaque' => 1,
                'monthly_charge' => 459480000
            ],
            [
                'plaque' => 8,
                'monthly_charge' => 81760000
            ],
            [
                'plaque' => 9,
                'monthly_charge' => 63180000
            ],
            [
                'plaque' => 10,
                'monthly_charge' => 40780000
            ],
            [
                'plaque' => 11,
                'monthly_charge' => 59680000
            ],
            [
                'plaque' => 12,
                'monthly_charge' => 43540000
            ],
            [
                'plaque' => 13,
                'monthly_charge' => 51700000
            ],
            [
                'plaque' => 14,
                'monthly_charge' => 49800000
            ],
            [
                'plaque' => 15,
                'monthly_charge' => 50680000
            ],
            [
                'plaque' => 16,
                'monthly_charge' => 80740000
            ],
            [
                'plaque' => 17,
                'monthly_charge' => 89000000
            ],
            [
                'plaque' => 18,
                'monthly_charge' => 84360000
            ],
            [
                'plaque' => 19,
                'monthly_charge' => 52640000
            ],
            [
                'plaque' => 20,
                'monthly_charge' => 63560000
            ],
            [
                'plaque' => 21,
                'monthly_charge' => 85900000
            ],
            [
                'plaque' => 22,
                'monthly_charge' => 102600000
            ],
            [
                'plaque' => 23,
                'monthly_charge' => 73040000
            ],
            [
                'plaque' => 24,
                'monthly_charge' => 35020000
            ],
            [
                'plaque' => 25,
                'monthly_charge' => 32620000
            ],
            [
                'plaque' => 26,
                'monthly_charge' => 70820000
            ],
            [
                'plaque' => 27,
                'monthly_charge' => 69800000
            ],
            [
                'plaque' => 28,
                'monthly_charge' => 49520000
            ],
            [
                'plaque' => 29,
                'monthly_charge' => 76320000
            ],
            [
                'plaque' => 30,
                'monthly_charge' => 49720000
            ],
            [
                'plaque' => 31,
                'monthly_charge' => 68560000
            ],
            [
                'plaque' => 32,
                'monthly_charge' => 65260000
            ],
            [
                'plaque' => 33,
                'monthly_charge' => 50000000
            ],
            [
                'plaque' => 34,
                'monthly_charge' => 52080000
            ],
            [
                'plaque' => 35,
                'monthly_charge' => 56780000
            ],
            [
                'plaque' => 36,
                'monthly_charge' => 62340000
            ],
            [
                'plaque' => 37,
                'monthly_charge' => 71680000
            ],
            [
                'plaque' => 38,
                'monthly_charge' => 74720000
            ],
            [
                'plaque' => 39,
                'monthly_charge' => 74440000
            ],
            [
                'plaque' => 40,
                'monthly_charge' => 85180000
            ],
            [
                'plaque' => 41,
                'monthly_charge' => 84280000
            ],
            [
                'plaque' => 42,
                'monthly_charge' => 59500000
            ],
            [
                'plaque' => 43,
                'monthly_charge' => 85700000
            ],
            [
                'plaque' => 44,
                'monthly_charge' => 55700000
            ],
            [
                'plaque' => 45,
                'monthly_charge' => 45620000
            ],
            [
                'plaque' => 46,
                'monthly_charge' => 56160000
            ],
            [
                'plaque' => 47,
                'monthly_charge' => 44280000
            ],
            [
                'plaque' => 48,
                'monthly_charge' => 62380000
            ],
            [
                'plaque' => 49,
                'monthly_charge' => 87680000
            ],
            [
                'plaque' => 50,
                'monthly_charge' => 64440000
            ],
            [
                'plaque' => 51,
                'monthly_charge' => 76760000
            ],
            [
                'plaque' => 52,
                'monthly_charge' => 70380000
            ],
            [
                'plaque' => 53,
                'monthly_charge' => 61400000
            ],
            [
                'plaque' => 54,
                'monthly_charge' => 53500000
            ],
            [
                'plaque' => 55,
                'monthly_charge' => 53960000
            ],
            [
                'plaque' => 56,
                'monthly_charge' => 48060000
            ],
            [
                'plaque' => 57,
                'monthly_charge' => 63120000
            ],
            [
                'plaque' => 58,
                'monthly_charge' => 56160000
            ],
            [
                'plaque' => 61,
                'monthly_charge' => 55560000
            ],
            [
                'plaque' => 62,
                'monthly_charge' => 49280000
            ],
            [
                'plaque' => 63,
                'monthly_charge' => 62400000
            ],
            [
                'plaque' => 64,
                'monthly_charge' => 50480000
            ],
            [
                'plaque' => 65,
                'monthly_charge' => 104320000
            ],
            [
                'plaque' => 66,
                'monthly_charge' => 49720000
            ],
            [
                'plaque' => 67,
                'monthly_charge' => 51860000
            ],
            [
                'plaque' => 68,
                'monthly_charge' => 35100000
            ],
            [
                'plaque' => 69,
                'monthly_charge' => 64380000
            ],
            [
                'plaque' => 70,
                'monthly_charge' => 68880000
            ],
            [
                'plaque' => 71,
                'monthly_charge' => 64920000
            ],
            [
                'plaque' => 72,
                'monthly_charge' => 56280000
            ],
            [
                'plaque' => 73,
                'monthly_charge' => 61580000
            ],
            [
                'plaque' => 74,
                'monthly_charge' => 56420000
            ],
            [
                'plaque' => 75,
                'monthly_charge' => 44740000
            ],
            [
                'plaque' => 76,
                'monthly_charge' => 46980000
            ],
            [
                'plaque' => 77,
                'monthly_charge' => 47720000
            ],
            [
                'plaque' => 78,
                'monthly_charge' => 48920000
            ],
            [
                'plaque' => 79,
                'monthly_charge' => 48720000
            ],
            [
                'plaque' => 80,
                'monthly_charge' => 32620000
            ],
            [
                'plaque' => 81,
                'monthly_charge' => 56640000
            ],
            [
                'plaque' => 82,
                'monthly_charge' => 35000000
            ],
            [
                'plaque' => 83,
                'monthly_charge' => 43700000
            ],
            [
                'plaque' => 84,
                'monthly_charge' => 42640000
            ],
            [
                'plaque' => 85,
                'monthly_charge' => 43260000
            ],
            [
                'plaque' => 86,
                'monthly_charge' => 42500000
            ],
            [
                'plaque' => 87,
                'monthly_charge' => 41060000
            ],
            [
                'plaque' => 88,
                'monthly_charge' => 48020000
            ],
            [
                'plaque' => 89,
                'monthly_charge' => 46980000
            ],
            [
                'plaque' => 90,
                'monthly_charge' => 44780000
            ],
            [
                'plaque' => 91,
                'monthly_charge' => 45780000
            ],
            [
                'plaque' => 92,
                'monthly_charge' => 40220000
            ],
            [
                'plaque' => 93,
                'monthly_charge' => 49320000
            ],
            [
                'plaque' => 94,
                'monthly_charge' => 49860000
            ],
            [
                'plaque' => 95,
                'monthly_charge' => 49600000
            ],
            [
                'plaque' => 96,
                'monthly_charge' => 49100000
            ],
            [
                'plaque' => 97,
                'monthly_charge' => 49420000
            ],
            [
                'plaque' => 98,
                'monthly_charge' => 46440000
            ],
            [
                'plaque' => 99,
                'monthly_charge' => 58940000
            ],
            [
                'plaque' => 100,
                'monthly_charge' => 48360000
            ],
            [
                'plaque' => 101,
                'monthly_charge' => 48320000
            ],
            [
                'plaque' => 102,
                'monthly_charge' => 48400000
            ],
            [
                'plaque' => 103,
                'monthly_charge' => 48220000
            ],
            [
                'plaque' => 104,
                'monthly_charge' => 44540000
            ],
            [
                'plaque' => 105,
                'monthly_charge' => 37440000
            ],
            [
                'plaque' => 106,
                'monthly_charge' => 55560000
            ],
            [
                'plaque' => 107,
                'monthly_charge' => 30540000
            ],
            [
                'plaque' => 108,
                'monthly_charge' => 64940000
            ],
            [
                'plaque' => 109,
                'monthly_charge' => 67580000
            ],
            [
                'plaque' => 110,
                'monthly_charge' => 54800000
            ],
            [
                'plaque' => 111,
                'monthly_charge' => 65060000
            ],
            [
                'plaque' => 112,
                'monthly_charge' => 58540000
            ],
            [
                'plaque' => 113,
                'monthly_charge' => 51760000
            ],
            [
                'plaque' => 114,
                'monthly_charge' => 33560000
            ],
            [
                'plaque' => 115,
                'monthly_charge' => 63740000
            ],
            [
                'plaque' => 201,
                'monthly_charge' => 360700000
            ],
            [
                'plaque' => 202,
                'monthly_charge' => 47900000
            ],
            [
                'plaque' => 203,
                'monthly_charge' => 55800000
            ],
            [
                'plaque' => 204,
                'monthly_charge' => 69660000
            ],
            [
                'plaque' => 205,
                'monthly_charge' => 50260000
            ],
            [
                'plaque' => 206,
                'monthly_charge' => 48560000
            ],
            [
                'plaque' => 207,
                'monthly_charge' => 79820000
            ],
            [
                'plaque' => 208,
                'monthly_charge' => 36620000
            ],
            [
                'plaque' => 209,
                'monthly_charge' => 39200000
            ],
            [
                'plaque' => 210,
                'monthly_charge' => 64640000
            ],
            [
                'plaque' => 211,
                'monthly_charge' => 64820000
            ],
            [
                'plaque' => 212,
                'monthly_charge' => 65420000
            ],
            [
                'plaque' => 213,
                'monthly_charge' => 54860000
            ],
            [
                'plaque' => 214,
                'monthly_charge' => 72260000
            ],
            [
                'plaque' => 215,
                'monthly_charge' => 59760000
            ],
            [
                'plaque' => 216,
                'monthly_charge' => 82480000
            ],
            [
                'plaque' => 217,
                'monthly_charge' => 72180000
            ],
            [
                'plaque' => 218,
                'monthly_charge' => 94840000
            ],
            [
                'plaque' => 219,
                'monthly_charge' => 65960000
            ],
            [
                'plaque' => 220,
                'monthly_charge' => 41420000
            ],
            [
                'plaque' => 221,
                'monthly_charge' => 41760000
            ],
            [
                'plaque' => 222,
                'monthly_charge' => 47580000
            ],
            [
                'plaque' => 223,
                'monthly_charge' => 43360000
            ],
            [
                'plaque' => 224,
                'monthly_charge' => 71580000
            ],
            [
                'plaque' => 226,
                'monthly_charge' => 58100000
            ],
            [
                'plaque' => 227,
                'monthly_charge' => 71200000
            ],
            [
                'plaque' => 228,
                'monthly_charge' => 72060000
            ],
            [
                'plaque' => 229,
                'monthly_charge' => 66220000
            ],
            [
                'plaque' => 230,
                'monthly_charge' => 64280000
            ],
            [
                'plaque' => 231,
                'monthly_charge' => 69120000
            ],
            [
                'plaque' => 232,
                'monthly_charge' => 106920000
            ],
            [
                'plaque' => 233,
                'monthly_charge' => 116800000
            ],
            [
                'plaque' => 234,
                'monthly_charge' => 118780000
            ],
            [
                'plaque' => 235,
                'monthly_charge' => 119740000
            ],
            [
                'plaque' => 236,
                'monthly_charge' => 138500000
            ],
            [
                'plaque' => 237,
                'monthly_charge' => 121900000
            ],
            [
                'plaque' => 238,
                'monthly_charge' => 91200000
            ],
            [
                'plaque' => 239,
                'monthly_charge' => 44840000
            ],
            [
                'plaque' => 240,
                'monthly_charge' => 49100000
            ],
            [
                'plaque' => 241,
                'monthly_charge' => 98080000
            ],
            [
                'plaque' => 242,
                'monthly_charge' => 81740000
            ],
            [
                'plaque' => 243,
                'monthly_charge' => 83380000
            ],
            [
                'plaque' => 244,
                'monthly_charge' => 69240000
            ],
            [
                'plaque' => 245,
                'monthly_charge' => 74180000
            ],
            [
                'plaque' => 246,
                'monthly_charge' => 95560000
            ],
            [
                'plaque' => 247,
                'monthly_charge' => 53760000
            ],
            [
                'plaque' => 248,
                'monthly_charge' => 68100000
            ],
            [
                'plaque' => 249,
                'monthly_charge' => 80440000
            ],
            [
                'plaque' => 250,
                'monthly_charge' => 85140000
            ],
            [
                'plaque' => 251,
                'monthly_charge' => 94540000
            ],
            [
                'plaque' => 252,
                'monthly_charge' => 123040000
            ],
            [
                'plaque' => 253,
                'monthly_charge' => 51580000
            ],
            [
                'plaque' => 254,
                'monthly_charge' => 51680000
            ],
            [
                'plaque' => 255,
                'monthly_charge' => 84300000
            ],
            [
                'plaque' => 256,
                'monthly_charge' => 64720000
            ],
            [
                'plaque' => 257,
                'monthly_charge' => 57840000
            ],
            [
                'plaque' => 258,
                'monthly_charge' => 41820000
            ],
            [
                'plaque' => 259,
                'monthly_charge' => 55920000
            ],
            [
                'plaque' => 260,
                'monthly_charge' => 75940000
            ],
            [
                'plaque' => 261,
                'monthly_charge' => 186360000
            ],
            [
                'plaque' => 262,
                'monthly_charge' => 251100000
            ],
            [
                'plaque' => 263,
                'monthly_charge' => 47000000
            ],
            [
                'plaque' => 264,
                'monthly_charge' => 42700000
            ],
            [
                'plaque' => 265,
                'monthly_charge' => 41900000
            ],
            [
                'plaque' => 266,
                'monthly_charge' => 48960000
            ],
            [
                'plaque' => 267,
                'monthly_charge' => 39940000
            ],
            [
                'plaque' => 268,
                'monthly_charge' => 52920000
            ],
            [
                'plaque' => 269,
                'monthly_charge' => 34880000
            ],
            [
                'plaque' => 270,
                'monthly_charge' => 77780000
            ],
            [
                'plaque' => 271,
                'monthly_charge' => 59560000
            ],
            [
                'plaque' => 272,
                'monthly_charge' => 69860000
            ],
            [
                'plaque' => 273,
                'monthly_charge' => 93460000
            ],
            [
                'plaque' => 301,
                'monthly_charge' => 153620000
            ],
            [
                'plaque' => 302,
                'monthly_charge' => 59180000
            ],
            [
                'plaque' => 303,
                'monthly_charge' => 43720000
            ],
            [
                'plaque' => 304,
                'monthly_charge' => 43140000
            ],
            [
                'plaque' => 305,
                'monthly_charge' => 43780000
            ],
            [
                'plaque' => 306,
                'monthly_charge' => 60160000
            ],
            [
                'plaque' => 307,
                'monthly_charge' => 93100000
            ],
            [
                'plaque' => 308,
                'monthly_charge' => 55920000
            ],
            [
                'plaque' => 309,
                'monthly_charge' => 28300000
            ],
            [
                'plaque' => 310,
                'monthly_charge' => 36720000
            ],
            [
                'plaque' => 311,
                'monthly_charge' => 57740000
            ],
            [
                'plaque' => 312,
                'monthly_charge' => 55960000
            ],
            [
                'plaque' => 313,
                'monthly_charge' => 38180000
            ],
            [
                'plaque' => 314,
                'monthly_charge' => 39780000
            ],
            [
                'plaque' => 315,
                'monthly_charge' => 42260000
            ],
            [
                'plaque' => 316,
                'monthly_charge' => 56760000
            ],
            [
                'plaque' => 317,
                'monthly_charge' => 57600000
            ],
            [
                'plaque' => 318,
                'monthly_charge' => 36380000
            ],
            [
                'plaque' => 319,
                'monthly_charge' => 67380000
            ],
            [
                'plaque' => 320,
                'monthly_charge' => 51220000
            ],
            [
                'plaque' => 321,
                'monthly_charge' => 81960000
            ],
            [
                'plaque' => 322,
                'monthly_charge' => 84680000
            ],
            [
                'plaque' => 323,
                'monthly_charge' => 71420000
            ],
            [
                'plaque' => 324,
                'monthly_charge' => 60420000
            ],
            [
                'plaque' => 325,
                'monthly_charge' => 65200000
            ],
            [
                'plaque' => 326,
                'monthly_charge' => 64820000
            ],
            [
                'plaque' => 327,
                'monthly_charge' => 79140000
            ],
            [
                'plaque' => 328,
                'monthly_charge' => 90880000
            ],
            [
                'plaque' => 329,
                'monthly_charge' => 56460000
            ],
            [
                'plaque' => 330,
                'monthly_charge' => 56380000
            ],
            [
                'plaque' => 331,
                'monthly_charge' => 50580000
            ],
            [
                'plaque' => 332,
                'monthly_charge' => 71440000
            ],
            [
                'plaque' => 333,
                'monthly_charge' => 71820000
            ],
            [
                'plaque' => 334,
                'monthly_charge' => 57340000
            ],
            [
                'plaque' => 335,
                'monthly_charge' => 58640000
            ],
            [
                'plaque' => 336,
                'monthly_charge' => 61700000
            ],
            [
                'plaque' => 337,
                'monthly_charge' => 55100000
            ],
            [
                'plaque' => 338,
                'monthly_charge' => 79900000
            ],
            [
                'plaque' => 339,
                'monthly_charge' => 82440000
            ],
            [
                'plaque' => 340,
                'monthly_charge' => 74860000
            ],
            [
                'plaque' => 341,
                'monthly_charge' => 49680000
            ],
            [
                'plaque' => 342,
                'monthly_charge' => 25540000
            ],
            [
                'plaque' => 343,
                'monthly_charge' => 57580000
            ],
            [
                'plaque' => 344,
                'monthly_charge' => 44840000
            ],
            [
                'plaque' => 345,
                'monthly_charge' => 63000000
            ],
            [
                'plaque' => 346,
                'monthly_charge' => 75940000
            ],
            [
                'plaque' => 347,
                'monthly_charge' => 47960000
            ],
            [
                'plaque' => 348,
                'monthly_charge' => 60420000
            ],
            [
                'plaque' => 349,
                'monthly_charge' => 55060000
            ],
            [
                'plaque' => 350,
                'monthly_charge' => 70380000
            ],
            [
                'plaque' => 351,
                'monthly_charge' => 98260000
            ],
            [
                'plaque' => 352,
                'monthly_charge' => 39080000
            ],
            [
                'plaque' => 353,
                'monthly_charge' => 40240000
            ],
            [
                'plaque' => 354,
                'monthly_charge' => 51680000
            ],
            [
                'plaque' => 355,
                'monthly_charge' => 26800000
            ],
            [
                'plaque' => 356,
                'monthly_charge' => 92720000
            ],
            [
                'plaque' => 357,
                'monthly_charge' => 44080000
            ],
            [
                'plaque' => 358,
                'monthly_charge' => 40300000
            ],
            [
                'plaque' => 359,
                'monthly_charge' => 62020000
            ],
            [
                'plaque' => 360,
                'monthly_charge' => 69080000
            ],
            [
                'plaque' => 361,
                'monthly_charge' => 40740000
            ],
            [
                'plaque' => 362,
                'monthly_charge' => 48140000
            ],
            [
                'plaque' => 363,
                'monthly_charge' => 59760000
            ],
            [
                'plaque' => 364,
                'monthly_charge' => 34980000
            ],
            [
                'plaque' => 365,
                'monthly_charge' => 37780000
            ],
            [
                'plaque' => 366,
                'monthly_charge' => 61460000
            ],
            [
                'plaque' => 367,
                'monthly_charge' => 62620000
            ],
            [
                'plaque' => 368,
                'monthly_charge' => 82700000
            ],
            [
                'plaque' => 369,
                'monthly_charge' => 95140000
            ],
            [
                'plaque' => 370,
                'monthly_charge' => 72660000
            ],
            [
                'plaque' => 371,
                'monthly_charge' => 73180000
            ],
            [
                'plaque' => 372,
                'monthly_charge' => 44580000
            ],
            [
                'plaque' => 373,
                'monthly_charge' => 45660000
            ],
            [
                'plaque' => 374,
                'monthly_charge' => 70780000
            ],
            [
                'plaque' => 375,
                'monthly_charge' => 43720000
            ],
            [
                'plaque' => 376,
                'monthly_charge' => 44180000
            ],
            [
                'plaque' => 377,
                'monthly_charge' => 72980000
            ],
            [
                'plaque' => 378,
                'monthly_charge' => 33580000
            ],
            [
                'plaque' => 379,
                'monthly_charge' => 37420000
            ],
            [
                'plaque' => 380,
                'monthly_charge' => 36120000
            ],
            [
                'plaque' => 381,
                'monthly_charge' => 34500000
            ],
            [
                'plaque' => 382,
                'monthly_charge' => 57720000
            ],
            [
                'plaque' => 383,
                'monthly_charge' => 46100000
            ],
            [
                'plaque' => 384,
                'monthly_charge' => 55180000
            ],
            [
                'plaque' => 385,
                'monthly_charge' => 67780000
            ],
            [
                'plaque' => 386,
                'monthly_charge' => 32360000
            ],
            [
                'plaque' => 387,
                'monthly_charge' => 31660000
            ],
            [
                'plaque' => 388,
                'monthly_charge' => 33820000
            ],
            [
                'plaque' => 401,
                'monthly_charge' => 90260000
            ],
            [
                'plaque' => 402,
                'monthly_charge' => 100460000
            ],
            [
                'plaque' => 403,
                'monthly_charge' => 101420000
            ],
            [
                'plaque' => 404,
                'monthly_charge' => 114420000
            ],
            [
                'plaque' => 405,
                'monthly_charge' => 98480000
            ],
            [
                'plaque' => 406,
                'monthly_charge' => 95560000
            ],
            [
                'plaque' => 407,
                'monthly_charge' => 92520000
            ],
            [
                'plaque' => 408,
                'monthly_charge' => 91820000
            ],
            [
                'plaque' => 409,
                'monthly_charge' => 59040000
            ],
            [
                'plaque' => 410,
                'monthly_charge' => 102820000
            ],
            [
                'plaque' => 411,
                'monthly_charge' => 84740000
            ],
            [
                'plaque' => 412,
                'monthly_charge' => 82860000
            ],
            [
                'plaque' => 413,
                'monthly_charge' => 86140000
            ],
            [
                'plaque' => 414,
                'monthly_charge' => 71820000
            ],
            [
                'plaque' => 415,
                'monthly_charge' => 100160000
            ],
            [
                'plaque' => 416,
                'monthly_charge' => 77560000
            ],
            [
                'plaque' => 417,
                'monthly_charge' => 76280000
            ],
            [
                'plaque' => 418,
                'monthly_charge' => 57100000
            ],
            [
                'plaque' => 419,
                'monthly_charge' => 81980000
            ],
            [
                'plaque' => 420,
                'monthly_charge' => 97900000
            ],
            [
                'plaque' => 421,
                'monthly_charge' => 97200000
            ],
            [
                'plaque' => 422,
                'monthly_charge' => 72980000
            ],
            [
                'plaque' => 423,
                'monthly_charge' => 65920000
            ],
            [
                'plaque' => 424,
                'monthly_charge' => 94400000
            ],
            [
                'plaque' => 425,
                'monthly_charge' => 108320000
            ],
            [
                'plaque' => 426,
                'monthly_charge' => 83020000
            ],
            [
                'plaque' => 501,
                'monthly_charge' => 90260000
            ],
            [
                'plaque' => 502,
                'monthly_charge' => 100460000
            ],
            [
                'plaque' => 503,
                'monthly_charge' => 101420000
            ],
            [
                'plaque' => 504,
                'monthly_charge' => 114420000
            ],
            [
                'plaque' => 505,
                'monthly_charge' => 98480000
            ],
            [
                'plaque' => 506,
                'monthly_charge' => 95560000
            ],
            [
                'plaque' => 507,
                'monthly_charge' => 92520000
            ],
            [
                'plaque' => 508,
                'monthly_charge' => 91820000
            ],
            [
                'plaque' => 509,
                'monthly_charge' => 59040000
            ],
            [
                'plaque' => 510,
                'monthly_charge' => 102820000
            ],
            [
                'plaque' => 511,
                'monthly_charge' => 84740000
            ],
            [
                'plaque' => 512,
                'monthly_charge' => 82860000
            ],
            [
                'plaque' => 513,
                'monthly_charge' => 86140000
            ],
            [
                'plaque' => 514,
                'monthly_charge' => 71820000
            ],
            [
                'plaque' => 515,
                'monthly_charge' => 100160000
            ],
            [
                'plaque' => 516,
                'monthly_charge' => 77560000
            ],
            [
                'plaque' => 517,
                'monthly_charge' => 76280000
            ],
            [
                'plaque' => 518,
                'monthly_charge' => 57100000
            ],
            [
                'plaque' => 519,
                'monthly_charge' => 81980000
            ],
            [
                'plaque' => 520,
                'monthly_charge' => 97900000
            ],
            [
                'plaque' => 521,
                'monthly_charge' => 97200000
            ],
            [
                'plaque' => 522,
                'monthly_charge' => 72980000
            ],
            [
                'plaque' => 523,
                'monthly_charge' => 65920000
            ],
            [
                'plaque' => 524,
                'monthly_charge' => 94400000
            ],
            [
                'plaque' => 525,
                'monthly_charge' => 108320000
            ],
            [
                'plaque' => 526,
                'monthly_charge' => 83020000
            ],
            [
                'plaque' => 601,
                'monthly_charge' => 90260000
            ],
            [
                'plaque' => 602,
                'monthly_charge' => 100460000
            ],
            [
                'plaque' => 603,
                'monthly_charge' => 101420000
            ],
            [
                'plaque' => 604,
                'monthly_charge' => 114420000
            ],
            [
                'plaque' => 605,
                'monthly_charge' => 98480000
            ],
            [
                'plaque' => 606,
                'monthly_charge' => 95560000
            ],
            [
                'plaque' => 607,
                'monthly_charge' => 92520000
            ],
            [
                'plaque' => 608,
                'monthly_charge' => 91820000
            ],
            [
                'plaque' => 609,
                'monthly_charge' => 59040000
            ],
            [
                'plaque' => 610,
                'monthly_charge' => 102820000
            ],
            [
                'plaque' => 611,
                'monthly_charge' => 84740000
            ],
            [
                'plaque' => 612,
                'monthly_charge' => 82860000
            ],
            [
                'plaque' => 613,
                'monthly_charge' => 86140000
            ],
            [
                'plaque' => 614,
                'monthly_charge' => 71820000
            ],
            [
                'plaque' => 615,
                'monthly_charge' => 100160000
            ],
            [
                'plaque' => 616,
                'monthly_charge' => 77560000
            ],
            [
                'plaque' => 617,
                'monthly_charge' => 76280000
            ],
            [
                'plaque' => 618,
                'monthly_charge' => 57100000
            ],
            [
                'plaque' => 619,
                'monthly_charge' => 81980000
            ],
            [
                'plaque' => 620,
                'monthly_charge' => 97900000
            ],
            [
                'plaque' => 621,
                'monthly_charge' => 97200000
            ],
            [
                'plaque' => 622,
                'monthly_charge' => 72980000
            ],
            [
                'plaque' => 623,
                'monthly_charge' => 65920000
            ],
            [
                'plaque' => 624,
                'monthly_charge' => 94400000
            ],
            [
                'plaque' => 625,
                'monthly_charge' => 108320000
            ],
            [
                'plaque' => 626,
                'monthly_charge' => 83020000
            ],
        ];
    }
}

