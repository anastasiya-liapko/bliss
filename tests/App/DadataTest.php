<?php

namespace Tests\App;

use App\Dadata;
use App\SMSRu;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class DadataTest.
 *
 * @package Tests\App
 */
class DadataTest extends TestCase
{
    /**
     * Tests the sendAlarmByTelegramBot method.
     *
     * @return void
     */
    public function testSendAlarmByTelegramBot(): void
    {
        $this->assertTrue(method_exists(SMSRu::class, 'sendAlarmByTelegramBot'));
    }

    /**
     * Tests the cleanAddress method.
     *
     * @return void
     * @throws \Exception
     */
    public function testCleanAddress(): void
    {
        $mock_telegram_bot = new MockHandler([
            new Response(200, [], '{"ok":true,"result":{}}'),
        ]);

        $handler_telegram_bot = HandlerStack::create($mock_telegram_bot);

        $mock = new MockHandler([
            new Response(
                200,
                [],
                '[{"source":"г. Москва ул. Ленина, 5","result":"г Москва, п Толстопальцево, ул Ленина, д 5",
                "postal_code":"108809","country":"Россия","federal_district":"Центральный",
                "region_fias_id":"0c5b2444-70a0-4932-980c-b4dc0d3f02b5","region_kladr_id":"7700000000000",
                "region_with_type":"г Москва","region_type":"г","region_type_full":"город","region":"Москва",
                "area_fias_id":null,"area_kladr_id":null,"area_with_type":null,"area_type":null,"area_type_full":null,
                "area":null,"city_fias_id":null,"city_kladr_id":null,"city_with_type":null,"city_type":null,
                "city_type_full":null,"city":null,"city_area":"Западный","city_district_fias_id":null,
                "city_district_kladr_id":null,"city_district_with_type":"р-н Внуково","city_district_type":"р-н",
                "city_district_type_full":"район","city_district":"Внуково",
                "settlement_fias_id":"9e967ebb-9993-433f-94d7-e29ab823f359","settlement_kladr_id":"7700000003300",
                "settlement_with_type":"п Толстопальцево","settlement_type":"п","settlement_type_full":"поселок",
                "settlement":"Толстопальцево","street_fias_id":"4350977e-e152-4018-a33d-b13143bbecda",
                "street_kladr_id":"77000000033168300","street_with_type":"ул Ленина","street_type":"ул",
                "street_type_full":"улица","street":"Ленина","house_fias_id":"76c3a03a-d911-4efa-be95-0b6c1d6e1c24",
                "house_kladr_id":"7700000003316830005","house_type":"д","house_type_full":"дом","house":"5",
                "block_type":null,"block_type_full":null,"block":null,"flat_type":null,"flat_type_full":null,
                "flat":null,"flat_area":null,"square_meter_price":"111266","flat_price":null,"postal_box":null,
                "fias_id":"76c3a03a-d911-4efa-be95-0b6c1d6e1c24","fias_code":"77000000033000016830005",
                "fias_level":"8","fias_actuality_state":"0","kladr_id":"7700000003316830005","capital_marker":"0",
                "okato":"45268552000","oktmo":"45317000","tax_office":"7729","tax_office_legal":"7729",
                "timezone":"UTC+3","geo_lat":"55.6120426","geo_lon":"37.2007264","beltway_hit":"OUT_MKAD",
                "beltway_distance":"16","qc_geo":0,"qc_complete":9,"qc_house":2,"qc":3,"unparsed_parts":null,
                "metro":null}]'
            ),
            new RequestException(
                'Unauthorized Server',
                new Request('POST', 'clean/address'),
                new Response(
                    401,
                    [],
                    '{"detail":"Format of the given token seems invalid. Please, verify it in the profile page."}'
                )
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $morpher = new Dadata($handler, $handler_telegram_bot);
        $this->assertEquals(
            'г Москва, п Толстопальцево, ул Ленина, д 5',
            $morpher->cleanAddress('г. Москва ул. Ленина, 5')
        );
        $this->assertFalse($morpher->cleanAddress('г. Москва ул. Ленина, 5'));

        $mock = new MockHandler([
            new RequestException(
                'Unauthorized Server',
                new Request('POST', 'clean/address'),
                new Response(
                    401,
                    [],
                    '{"detail":"Format of the given token seems invalid. Please, verify it in the profile page."}'
                )
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $morpher = new Dadata($handler, $handler_telegram_bot);
        $this->assertFalse($morpher->cleanAddress('г. Москва ул. Ленина, 5'));
    }
}
