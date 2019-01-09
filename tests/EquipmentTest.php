<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class EquipmentTest extends TestCase
{

    /**
     * /equipment [GET]
     */
    public function testShouldReturnAll(){
        $response = $this->json('GET', '/equipment', []);
        $response->seeStatusCode(200)
            ->seeJsonContains([
                "success" => true,
                "message" => "OK",
            ]);

    }
    /**
     * /equipment/id [GET]
     */
    public function testShouldReturnExist(){
        $this->get("equipment/10", []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'account_id',
                    'type_id',
                    'status_id',
                    'name',
                    'serial',
                    'model',
                    'bar_code',
                    'purchased_at',
                    'last_service_at',
                    'insurance_valid_until',
                    'registration_renewal_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        );
    }
    /**
     * /equipment/id [GET]
     */
    public function testShouldReturnNotExist(){
        $this->get("equipment/99999999", []);
        $this->seeStatusCode(404);
        $this->seeJsonContains([
            "success" => false,
            "message" => "Not Found",
        ]);
    }
    /**
     * /products [POST]
     */
    public function testShouldCreate(){
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $parameters = [
            'account_id' => 1,
            'type_id' => 1,
            'status_id' => 1,
            'name' => 'Unit test item',
            'description' => 'Unit test for item create',
            'serial' => 'serial',
            'model' => 'model',
            'bar_code' => 'bar_code',
            'purchased_at' => $now,
            'last_service_at' => $now,
            'next_service_at' => $now,
            'insurance_valid_until' => $now,
            'registration_renewal_at' => $now,
        ];
        $this->post("equipment", $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            ['data' =>
                [
                    'account_id',
                    'type_id',
                    'status_id',
                    'name',
                    'serial',
                    'model',
                    'bar_code',
                    'purchased_at',
                    'last_service_at',
                    'insurance_valid_until',
                    'registration_renewal_at',
                    'created_at',
                    'updated_at',
                ]
            ]
        );
    }

}
